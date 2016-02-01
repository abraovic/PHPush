<?php

require '../vendor/autoload.php';

use abraovic\PHPush;
use abraovic\PHPush\Exception\PHPushException;

try {

    $type = PHPush\Push\Push::ANDROID;
    $credentials = [
        'device_token' => 'fake-token',
        'google_api_key' => 'fake-api'
    ];

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