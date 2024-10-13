<?php

require __DIR__ . '/vendor/autoload.php'; // Ensure Composer dependencies are loaded

use Mqtt\MqttPhp\Controller\MqttServer; // Update to match the new namespace

// Create an instance of the MqttServer class
$mqttServer = new MqttServer();

// Start the MQTT server
$mqttServer->start();
