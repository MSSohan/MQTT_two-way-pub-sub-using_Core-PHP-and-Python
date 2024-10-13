<?php

namespace Controller;

require __DIR__ . '/../../vendor/autoload.php'; // Ensure Composer dependencies are loaded

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\Exceptions\MqttClientException;

class MqttServer
{
    private $client;

    public function __construct()
    {
        $config = [
            'server' => 'broker.emqx.io',
            'port' => 1883,
            'username' => '',  // Set if required
            'password' => '',  // Set if required
            'keepalive' => 60,
        ];

        $this->client = new MqttClient($config['server'], $config['port'], 'php-server-' . uniqid());
    }

    public function start()
    {
        try {
            $this->client->connect($config['username'], $config['password'], $config['keepalive']);
            $topic = 'uprint/kiosk';
            echo "Subscribed to topic '{$topic}'" . PHP_EOL;

            // Handle incoming messages
            $this->client->subscribe($topic, function ($topic, $message) {
                echo "Received message on topic '{$topic}' with payload: {$message}" . PHP_EOL;

                // You can parse the message and decide to publish a response
                $data = json_decode($message, true);
                if (json_last_error() === JSON_ERROR_NONE && isset($data['device_id'])) {
                    $deviceId = $data['device_id'];
                    $responseTopic = "uprint/kiosk/{$deviceId}";
                    $responseMessage = json_encode(['response' => 'Message received', 'device_id' => $deviceId]);

                    // Publish the response
                    $this->client->publish($responseTopic, $responseMessage, 1);
                    echo "Sent response '{$responseMessage}' to topic '{$responseTopic}'" . PHP_EOL;
                } else {
                    echo "Invalid message format" . PHP_EOL;
                }
            }, 1);  // QoS 1

            // Start the client loop to receive messages
            $this->client->loop(true);
        } catch (MqttClientException $e) {
            echo 'Failed: ' . $e->getMessage() . PHP_EOL;
        }
    }

    public function stop()
    {
        $this->client->disconnect();
    }
}
