<?php
require __DIR__ . '/vendor/autoload.php'; // Autoload Composer packages

use Mqtt\MqttPhp\Service\WebPageDesign;

// Include the WebPageDesign class
$webPage = new WebPageDesign();
$webPage->renderHeader(); // Render the header section
?>
<div class="container mt-4">
    <h1 class="text-center">Real-Time MQTT Data</h1>
    <div id="dataDisplay" class="card">
        <div class="card-body">
            <h5 class="card-title">Messages:</h5>
            <p class="card-text" id="messageContent">Waiting for messages...</p>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="script.js"></script>
<?php
$webPage->renderFooter(); // Render the footer section
?>
