<?php

require __DIR__ . '/vendor/autoload.php'; // Adjust the path if necessary

use Mqtt\MqttPhp\Controller\MqttServer;
use Mqtt\MqttPhp\Service\WebPageDesign;

// Create an instance of the MQTT server
$mqttServer = new MqttServer();

// Start the MQTT server
// $mqttServer->start();

// Serve the webpage
$webPage = new WebPageDesign();
$webPage->render();
