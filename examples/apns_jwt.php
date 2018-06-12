<?php

require '../vendor/autoload.php';

use abraovic\PHPush;
use abraovic\PHPush\Exception\PHPushException;

try {
    $kid = "your-kid";
    $iss = "your-iss";
    $key = "path-to-your-key";
    $secret = "secret-if-your-key-needsit";

    $type = PHPush\Push\Push::IOS_JWT;
    $jwt = new PHPush\iOS\JWT($kid, $iss, $key, $secret);

    $credentials = [
        'device_token' => 'fake-token', // for multiple use an array 'device_token' => ['fake-token-1', 'fake-token-2', ...]
        'app_bundle_id' => 'fake-topic',
        'jwt' => $jwt,
        'dev' => true
    ];

    // enable printing payload before send (for development purpose)
    PHPush\Push\Push::$printPayload = true;

    $push = new PHPush\Push\Push($type, $credentials);
    $message = new PHPush\Push\Message($type, "Hello");
    $message->setBadge(200);
    $iosMsg = $message->getMessage();
    $iosMsg->setSound('default');

    if ($push->sendMessage($message)) {
        echo "sent";
    }
} catch (PHPushException $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}