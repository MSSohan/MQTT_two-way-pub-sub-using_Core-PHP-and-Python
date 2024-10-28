<?php

namespace Mqtt\MqttPhp\Service;

class WebPageDesign {
    public function render() {
        // Start output buffering
        ob_start();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>MQTT Messages</title>
            <link rel="stylesheet" href="styles.css"> <!-- Link to your CSS file -->
            <script>
                function fetchMessages() {
                    fetch('src/Controller/fetch_messages.php') // Fetch from your PHP endpoint
                        .then(response => response.json())
                        .then(data => {
                            const messagesContainer = document.getElementById('messages');
                            messagesContainer.innerHTML = ''; // Clear previous messages
                            
                            data.forEach(msg => {
                                const messageElement = document.createElement('div');
                                messageElement.className = 'message';
                                messageElement.textContent = `${msg.id}:  
                                                                Device ID: ${msg.device_id}, 
                                                                Status: ${msg.status},
                                                                Time: ${msg.timestamp}`; // Adjust based on your message structure
                                messagesContainer.appendChild(messageElement);
                            });
                        })
                        .catch(error => console.error('Error fetching messages:', error));
                }

                // Fetch messages on page load and every 2 seconds
                window.onload = fetchMessages; // Fetch on page load
                setInterval(fetchMessages, 100); // Fetch messages every 2 seconds
            </script>
        </head>
        <body>
            <div class="container">
                <h1>MQTT Messages</h1>
                <div id="messages" class="messages-container"></div> <!-- Container for displaying messages -->
            </div>
        </body>
        </html>
        <?php
        // Get contents of the output buffer
        $output = ob_get_clean();
        echo $output; // Output the rendered HTML
    }
}
