<?php

require '../vendor/autoload.php';

use abraovic\PHPush;
use abraovic\PHPush\Exception\PHPushException;


try {

    $type = PHPush\Push\Push::IOS;
    $credentials = [
        'device_token' => 'paste-token-here',
        'certificate_path' => 'exported-cert.pem', // make sure it is pem
        'certificate_phrase' => 'pass-if-needed',
        'dev' => true // make sure this meets certificate
    ];

    $push = new PHPush\Push\Push($type, $credentials);
    $message = new PHPush\iOS\Message();
    $message->setAlert("This will be shown");
    $message->setBadge(2);
    $push->sendMessage($message);

} catch (PHPushException $e) {
    echo 'Caught exception: ' . $e->getMessage() . "\n";
}