<?php
namespace abraovic\PHPush\Push;

use abraovic\PHPush\Exception\PHPushException;
use abraovic\PHPush;
use Symfony\Component\Yaml\Yaml;

/**
 *     Copyright 2016
 *
 *     Licensed under the Apache License, Version 2.0 (the "License");
 *     you may not use this file except in compliance with the License.
 *     You may obtain a copy of the License at
 *
 *         http://www.apache.org/licenses/LICENSE-2.0
 *
 *     @author Ante Braović - abraovic@gmail.com - antebraovic.me
 *
 */

class Message implements PHPush\Message
{
    // define constants to determine which kind of a push notification
    // should be sent
    const IOS = 'iOS';
    const ANDROID = 'Android';

    private $message;

    /**
     * @param $type         ->  defines type of service identified by constants
     *                          example $type = Push::IOS
     * @param $title
     * @throws PHPushException
     */
    function __construct($type, $title)
    {
        $this->type = $type;

        switch ($type) {
            case self::IOS:
                $this->message = new PHPush\iOS\Message($title);
                break;
            case self::ANDROID:
                $this->message = new PHPush\Android\Message($title);
                break;
            default:
                throw new PHPushException(
                    "Unxeisting service called. Available services are [Push::IOS] and [Push::ANDROID]",
                    500
                );
                break;
        }
    }

    public function getMessage()
    {
        switch ($this->type) {
            case self::IOS:
                /** @var PHPush\iOS\Message $msg */
                $msg = $this->message;
                break;
            case self::ANDROID:
                /** @var PHPush\Android\Message $msg */
                $msg = $this->message;
                break;
            default:
                throw new PHPushException(
                    "Unxeisting service called. Available services are [Push::IOS] and [Push::ANDROID]",
                    500
                );
                break;
        }

        return $msg;
    }

    public function setBody($body)
    {
        $this->message->setBody($body);
    }

    public function setBadge($badge)
    {
        $this->message->setBadge($badge);
    }

    public function setAdditional($data)
    {
        $this->message->setAdditional($data);
    }
} 