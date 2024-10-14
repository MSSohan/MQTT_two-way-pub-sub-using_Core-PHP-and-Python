<?php

require __DIR__ . '/vendor/autoload.php';

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Exceptions\MqttClientException;
use PhpMqtt\Client\ConnectionSettings;

// MQTT settings
$MQTT_USER = ''; // Leave empty if not needed
$MQTT_PASSWORD = ''; // Leave empty if not needed
$MQTT_SERVER = 'broker.emqx.io';
$MQTT_PORT = 1883;
$MQTT_KEEPALIVE = 60;  // Keepalive interval in seconds
$TOPIC = 'uprint/kiosk';

try {
    // Create MQTT client instance
    $client = new MqttClient($MQTT_SERVER, $MQTT_PORT, uniqid('php-client-'));

    // Configure connection settings
    $settings = (new ConnectionSettings())
        ->setUsername(trim($MQTT_USER)) // Trim any whitespace
        ->setPassword(trim($MQTT_PASSWORD)); // Trim any whitespace

    // Connect to the broker with the specified keep-alive interval
    if (!empty($MQTT_USER)) {
        $client->connect($settings, true, $MQTT_KEEPALIVE);
    } else {
        $client->connect(null, true, $MQTT_KEEPALIVE); // Connect without credentials
    }

    echo "Connected successfully to the broker." . PHP_EOL;

    // Subscribe to the topic
    $client->subscribe($TOPIC, function ($topic, $message) use ($client) {
        echo "Received message on topic '{$topic}' with payload: {$message}" . PHP_EOL;

        // Handle the message and publish a response
        $data = json_decode($message, true);
        if (json_last_error() === JSON_ERROR_NONE && isset($data['device_id'])) {
            $deviceId = $data['device_id'];
            $responseTopic = "uprint/kiosk/{$deviceId}";
            $responseMessage = json_encode(['response' => 'Message received', 'device_id' => $deviceId]);

            $client->publish($responseTopic, $responseMessage, 1);
            echo "Sent response '{$responseMessage}' to topic '{$responseTopic}'" . PHP_EOL;
        } else {
            echo "Invalid message format" . PHP_EOL;
        }
    }, 1);

    echo "Subscribed to topic '{$TOPIC}'" . PHP_EOL;

    // Keep the client running to receive messages
    while ($client->isConnected()) {
        $client->loop(true);  // Block until a message arrives
        usleep(100000);  // Sleep for 100ms to reduce CPU usage
    }

    // Disconnect gracefully
    $client->disconnect();
    echo "Disconnected from the broker." . PHP_EOL;

} catch (MqttClientException $e) {
    echo 'An error occurred: ' . $e->getMessage() . PHP_EOL;
}
