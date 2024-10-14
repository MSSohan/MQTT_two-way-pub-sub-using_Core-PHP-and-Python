<?php

namespace Mqtt\MqttPhp\Service;

class WebPageDesign {
    public function render() {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>MQTT Message Viewer</title>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    margin: 0;
                    padding: 20px;
                }
                .container {
                    max-width: 600px;
                    margin: auto;
                    padding: 20px;
                    background: white;
                    border-radius: 5px;
                    box-shadow: 0 0 10px rgba(0,0,0,0.1);
                }
                h1 {
                    text-align: center;
                }
                .message {
                    background: #e2e2e2;
                    border-left: 4px solid #4CAF50;
                    margin: 10px 0;
                    padding: 10px;
                }
                .footer {
                    text-align: center;
                    margin-top: 20px;
                    font-size: 0.8em;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>MQTT Messages</h1>
                <div id="messages"></div>
                <div class="footer">Powered by MQTT PHP Client</div>
            </div>
            <script>
                const messagesDiv = document.getElementById('messages');

                // Function to fetch messages from the server
                async function fetchMessages() {
                    try {
                        const response = await fetch('Controller/getMessages.php');
                        const messages = await response.json();
                        messagesDiv.innerHTML = ''; // Clear existing messages
                        messages.forEach(msg => {
                            const messageDiv = document.createElement('div');
                            messageDiv.classList.add('message');
                            messageDiv.innerHTML = msg; // Assuming msg is already formatted
                            messagesDiv.appendChild(messageDiv);
                        });
                    } catch (error) {
                        console.error('Error fetching messages:', error);
                    }
                }

                // Fetch messages every 5 seconds
                setInterval(fetchMessages, 5000);
                // Initial fetch
                fetchMessages();
            </script>
        </body>
        </html>
        <?php
    }
}
