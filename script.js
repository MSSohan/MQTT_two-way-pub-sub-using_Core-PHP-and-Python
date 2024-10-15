// Assuming you have a WebSocket or another method to fetch messages
const messageContent = document.getElementById('messageContent');

// Example function to simulate receiving a message
function receiveMessage(message) {
    messageContent.innerText = message; // Update the display
}

// Example of how to call the MQTT client
function publishMessage(data) {
    // Send the MQTT publish request here using AJAX or Fetch API
}

// Simulate receiving messages for testing
setInterval(() => {
    const fakeMessage = `New message at ${new Date().toLocaleTimeString()}`;
    receiveMessage(fakeMessage);
}, 5000);
