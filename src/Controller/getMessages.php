<?php

require __DIR__ . '/../../vendor/autoload.php';  // Adjust path if necessary

use Mqtt\MqttPhp\Controller\MqttServer;

$server = new MqttServer();
$messages = $server->getMessages();

// Set content type to JSON and output the messages
header('Content-Type: application/json');
echo json_encode($messages);
