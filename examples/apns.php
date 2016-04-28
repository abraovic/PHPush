<?php

require '../vendor/autoload.php';

use abraovic\PHPush;
use abraovic\PHPush\Exception\PHPushException;

try {

    $type = PHPush\Push\Push::IOS;
    $credentials = [
        'device_token' => 'fake-token', // for multiple use an array 'device_token' => ['fake-token-1', 'fake-token-2', ...]
        'certificate_path' => 'fake-cert',
        'certificate_phrase' => '',
        'dev' => true
    ];

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