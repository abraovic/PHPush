<?php

require '../vendor/autoload.php';

use abraovic\PHPush;
use abraovic\PHPush\Exception\PHPushException;

try {

    $type = PHPush\Push\Push::ANDROID;
    $credentials = [
        'device_token' => 'fake-token', // for multiple use an array 'device_token' => ['fake-token-1', 'fake-token-2', ...]
        'google_api_key' => 'fake-api'
    ];
    
    // enable printing payload before send (for development purpose)
    PHPush\Push\Push::$printPayload = true;

    $push = new PHPush\Push\Push($type, $credentials);
    $message = new PHPush\Push\Message($type, "Hello");
    $message->setBadge(200);
    $message->setAdditional(["key" => "value"]);

    if ($push->sendMessage($message)) {
        echo "sent";
    }
} catch (PHPushException $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}