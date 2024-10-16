<?php

namespace Mqtt\MqttPhp\Controller; // Make sure to set the correct namespace

// Set the content type to JSON
header('Content-Type: application/json');

// Path to the messages file
$messagesFile = __DIR__ . '/../../messages.json'; // Adjust the path if necessary

// Initialize an array to hold messages
$messages = [];

// Check if the messages file exists
if (file_exists($messagesFile)) {
    // Read the contents of the file
    $fileContents = file_get_contents($messagesFile);
    
    // Decode the JSON data
    $messages = json_decode($fileContents, true);
    
    // Check for JSON errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        // If there's an error, return an empty array
        $messages = [];
    }
}

// Return the messages as a JSON response
echo json_encode($messages);
