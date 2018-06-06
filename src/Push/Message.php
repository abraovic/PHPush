<?php
namespace abraovic\PHPush\Push;

use abraovic\PHPush\Exception\PHPushException;
use abraovic\PHPush;

/**
 *     Copyright 2016
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
    private $type;

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

    /**
     * @return PHPush\Message
     * @throws PHPushException
     */
    public function getMessage(): PHPush\Message
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

    /**
     * @param $body
     * @return PHPush\Message
     */
    public function setBody(string $body): PHPush\Message
    {
        return $this->message->setBody($body);
    }

    /**
     * @param int $badge
     * @return PHPush\Message
     */
    public function setBadge(int $badge): PHPush\Message
    {
        return $this->message->setBadge($badge);
    }

    /**
     * @param $data
     */
    public function setAdditional(array $data): void
    {
        $this->message->setAdditional($data);
    }
} 