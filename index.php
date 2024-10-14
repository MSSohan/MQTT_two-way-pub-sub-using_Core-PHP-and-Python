<?php

require __DIR__ . '/vendor/autoload.php'; // Make sure to adjust the path if necessary

use Mqtt\MqttPhp\Controller\MqttServer;

// Create an instance of MqttServer
$mqttServer = new MqttServer();

// Start the MQTT server
$mqttServer->start();
