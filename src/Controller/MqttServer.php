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
    private $messagesFile = __DIR__ . '/../../messages.json'; // Path to store messages

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
                $this->handleMessage($topic, $message); // Handle the incoming message
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

    // Function to handle incoming messages
    private function handleMessage($topic, $message) {
        echo "Received message on topic '{$topic}' with payload: {$message}" . PHP_EOL;

        // Decode the message
        $data = json_decode($message, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($data['device_id'])) {
            $deviceId = $data['device_id'];
            $responseTopic = "uprint/kiosk/{$deviceId}";
            $responseMessage = json_encode(['response' => 'Message received', 'device_id' => $deviceId]);

            // Publish a response
            $this->client->publish($responseTopic, $responseMessage, 1);
            echo "Sent response '{$responseMessage}' to topic '{$responseTopic}'" . PHP_EOL;

            // Store the message in a file
            $this->storeMessage($message); // Call to the function that stores messages
        } else {
            echo "Invalid message format" . PHP_EOL;
        }
    }

    // Function to store messages in a JSON file
    private function storeMessage($message) {
        $messages = [];
    
        // Load existing messages if the file exists
        if (file_exists($this->messagesFile)) {
            $messages = json_decode(file_get_contents($this->messagesFile), true);
        }
    
        // Add the new message to the array
        $messages[] = $message;
    
        // Check if the total number of messages exceeds 500
        if (count($messages) >= 500) {
            // Clear the messages.json file
            file_put_contents($this->messagesFile, json_encode([])); // Write an empty array to clear the file
            echo "Messages.json cleared because the total number of messages reached 500." . PHP_EOL;
        } else {
            // Save the updated messages back to the file
            file_put_contents($this->messagesFile, json_encode($messages));
        }
    }
    
}
