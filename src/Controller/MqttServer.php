<?php

namespace Mqtt\MqttPhp\Controller;

require __DIR__ . '/../../vendor/autoload.php'; // Adjust the path if necessary

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\ConnectionSettings;

class MqttServer {
    private $client;
    private $MQTT_USER = '';  // Set if required
    private $MQTT_PASSWORD = '';  // Set if required
    private $MQTT_SERVER = 'broker.emqx.io';
    private $MQTT_PORT = 1883;
    private $TOPIC = 'uprint/kiosk';
    private $messageLogFile = __DIR__ . '/../../messages.log'; // File to log messages

    public function start() {
        set_time_limit(0); // Allow the script to run indefinitely

        try {
            // Create MQTT client instance
            $this->client = new MqttClient($this->MQTT_SERVER, $this->MQTT_PORT, uniqid('php-client-'));

            // Configure connection settings
            $settings = (new ConnectionSettings())
                ->setUsername(trim($this->MQTT_USER))
                ->setPassword(trim($this->MQTT_PASSWORD));

            // Connect to the broker
            if (!empty($this->MQTT_USER)) {
                $this->client->connect($settings);
            } else {
                $this->client->connect(null); // Connect without credentials
            }

            echo "Connected successfully to the broker." . PHP_EOL;

            // Subscribe to the topic
            $this->client->subscribe($this->TOPIC, function ($topic, $message) {
                echo "Received message on topic '{$topic}' with payload: {$message}" . PHP_EOL;

                // Log the message to a file
                file_put_contents($this->messageLogFile, date('Y-m-d H:i:s') . " - {$message}\n", FILE_APPEND);
            }, 1);

            echo "Subscribed to topic '{$this->TOPIC}'" . PHP_EOL;

            // Keep the client running to receive messages
            while ($this->client->isConnected()) {
                $this->client->loop(true);  // Block until a message arrives
                usleep(100000);  // Sleep for 100ms to reduce CPU usage
            }

            // Disconnect gracefully
            $this->client->disconnect();
            echo "Disconnected from the broker." . PHP_EOL;

        } catch (MqttClientException $e) {
            echo 'An error occurred: ' . $e->getMessage() . PHP_EOL;
        }
    }

    public function getMessages() {
        // Read messages from the log file
        if (file_exists($this->messageLogFile)) {
            return file($this->messageLogFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        }
        return [];
    }
}
