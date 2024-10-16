<?php

require __DIR__ . '/vendor/autoload.php'; // Adjust the path if necessary

use Mqtt\MqttPhp\Controller\MqttServer;
use Mqtt\MqttPhp\Service\WebPageDesign;

// Create an instance of MqttServer
$mqttServer = new MqttServer();

// Start the MQTT server in a separate thread or process if needed
$mqttServer->start(); // Uncomment this if you need to start the server here

// Create an instance of WebPageDesign
$page = new WebPageDesign();

// Render the webpage
$page->render();