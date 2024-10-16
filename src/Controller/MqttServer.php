<?php

namespace Mqtt\MqttPhp\Controller;  // Make sure to set the correct namespace

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
    private $MQTT_KEEPALIVE = 60;  // Keepalive interval in seconds
    private $TOPIC = 'uprint/kiosk';

    public function start() {
        set_time_limit(0); // Allow the script to run indefinitely
    
        try {
            // Create MQTT client instance
            $this->client = new MqttClient($this->MQTT_SERVER, $this->MQTT_PORT, uniqid('php-client-'));
    
            // Configure connection settings
            $settings = (new ConnectionSettings())
                ->setUsername(trim($this->MQTT_USER))
                ->setPassword(trim($this->MQTT_PASSWORD));
    
            // Connect to the broker with the specified keep-alive interval
            if (!empty($this->MQTT_USER)) {
                $this->client->connect($settings, true, $this->MQTT_KEEPALIVE);
            } else {
                $this->client->connect(null, true, $this->MQTT_KEEPALIVE); // Connect without credentials
            }
    
            echo "Connected successfully to the broker." . PHP_EOL;
    
            // Subscribe to the topic
            $this->client->subscribe($this->TOPIC, function ($topic, $message) {
                echo "Received message on topic '{$topic}' with payload: {$message}" . PHP_EOL;
    
                // Handle the message and publish a response
                $data = json_decode($message, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($data['device_id'])) {
                    $deviceId = $data['device_id'];
                    $responseTopic = "uprint/kiosk/{$deviceId}";
                    $responseMessage = json_encode(['response' => 'Message received', 'device_id' => $deviceId]);
    
                    $this->client->publish($responseTopic, $responseMessage, 1);
                    echo "Sent response '{$responseMessage}' to topic '{$responseTopic}'" . PHP_EOL;
                } else {
                    echo "Invalid message format" . PHP_EOL;
                }
            }, 1);
    
            echo "Subscribed to topic '{$this->TOPIC}'" . PHP_EOL;
    
            // Keep the client running to receive messages
            while ($this->client->isConnected()) {
                $this->client->loop(true);  // Block until a message arrives
                usleep(100000);  // Sleep for 100ms to reduce CPU usage
    
                // Send a keep-alive message every 30 seconds
                if (time() % 30 === 0) {
                    $this->client->ping();  // Ping to keep the connection alive
                }
            }
    
            // Disconnect gracefully
            $this->client->disconnect();
            echo "Disconnected from the broker." . PHP_EOL;
    
        } catch (MqttClientException $e) {
            echo 'An error occurred: ' . $e->getMessage() . PHP_EOL;
        }
    }    
}