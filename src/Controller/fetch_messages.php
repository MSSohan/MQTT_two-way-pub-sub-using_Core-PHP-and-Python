<?php

namespace Mqtt\MqttPhp\Controller;

require __DIR__ . '/../../vendor/autoload.php'; // Adjust the path if necessary

// Specify the path to the messages.json file
$messagesFile = __DIR__ . '/../../messages.json'; 

// Check if the file exists
if (file_exists($messagesFile)) {
    // Read the JSON file
    $content = file_get_contents($messagesFile);
    
    // Check if content was read successfully
    if ($content === false) {
        echo json_encode(['error' => 'Failed to read messages file']);
        exit;
    }

    $messages = json_decode($content, true); // Decode JSON into associative array

    // Check if the JSON was decoded correctly
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['error' => 'Failed to decode JSON: ' . json_last_error_msg()]);
        exit;
    }

    // Check if the data is indeed an array
    if (!is_array($messages)) {
        echo json_encode(['error' => 'Messages data is not an array']);
        exit;
    }

    // Sort the messages array in descending order (most recent first)
    usort($messages, function($a, $b) {
        return strtotime($b['timestamp']) - strtotime($a['timestamp']); // Sort based on timestamp
    });

    // Prepare a response array to include the messages
    $response = [];
    foreach ($messages as $data) {
        $response[] = [
            'id' => $data['id'] ?? 'unknown',
            'device_id' => $data['device_id'] ?? 'unknown', // Use 'unknown' if device_id is not set
            'status' => $data['status'] ?? 'No message', // Use a default message if not set
            'timestamp' => $data['timestamp'] ?? 'undefined' // Handle undefined timestamps
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($response);
} else {
    // Return an empty array if the file does not exist
    echo json_encode([]);
}
