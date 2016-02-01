<?php
namespace abraovic\PHPush\Android;

use abraovic\PHPush;
use abraovic\PHPush\Exception\PHPushException;

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
 *     For more documentation:
 *     @see: http://developer.android.com/google/gcm/server.html
 *           Table 1. Message parameters
 */

class Message implements PHPush\Message
{
    /** @var Notification $notification */
    private $notification;
    private $data = [];
    private $delayWithIdle;
    private $dryRun;
    private $collapseKey;
    private $additional;

    /**
     * @param string $title
     */
    function __construct($title)
    {
        $this->notification = new Notification($title);
    }

    public function getMessage()
    {
        return $this;
    }

    public function setBody($body)
    {
        $this->notification->setBody($body);
        return $this;
    }

    public function setBadge($badge)
    {
        $this->data['badge'] = $badge;
        $this->notification->setBadge($badge);
        return $this;
    }

    public function setAdditional($data)
    {
        $this->additional = $data;
    }

    /**
     * @param string $collapseKey
     * @return $this
     */
    public function setCollapseKey($collapseKey)
    {
        $this->collapseKey = $collapseKey;
        return $this;
    }

    /**
     * @param int $delayWithIdle
     * @return $this
     */
    public function setDelayWithIdle($delayWithIdle)
    {
        $this->delayWithIdle = $delayWithIdle;
        return $this;
    }

    /**
     * @param boolean $dryRun
     * @return $this
     */
    public function setDryRun($dryRun)
    {
        $this->dryRun = $dryRun;
        return $this;
    }

    /**
     * @param string $icon
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->notification->setIcon($icon);
        return $this;
    }

    /**
     * @param array $bodyLocArgs
     * @return $this
     */
    public function setBodyLocArgs($bodyLocArgs)
    {
        $this->notification->setBodyLocArgs($bodyLocArgs);
        return $this;
    }

    /**
     * @param string $bodyLocKey
     * @return $this
     */
    public function setBodyLocKey($bodyLocKey)
    {
        $this->notification->setBodyLocKey($bodyLocKey);
        return $this;
    }

    /**
     * @param string $clickAction
     * @return $this
     */
    public function setClickAction($clickAction)
    {
        $this->notification->setClickAction($clickAction);
        return $this;
    }

    /**
     * @param string $color
     * @return $this
     */
    public function setColor($color)
    {
        $this->notification->setColor($color);
        return $this;
    }

    /**
     * @param string $sound
     * @return $this
     */
    public function setSound($sound)
    {
        $this->notification->setSound($sound);
        return $this;
    }

    /**
     * @param string $tag
     * @return $this
     */
    public function setTag($tag)
    {
        $this->notification->setTag($tag);
        return $this;
    }

    /**
     * @param array $titleLocArgs
     * @return $this
     */
    public function setTitleLocArgs($titleLocArgs)
    {
        $this->notification->setTitleLocArgs($titleLocArgs);
        return $this;
    }

    /**
     * @param string $titleLocKey
     * @return $this
     */
    public function setTitleLocKey($titleLocKey)
    {
        $this->notification->setTitleLocKey($titleLocKey);
        return $this;
    }

    public function toArray()
    {
        $array = [
            'notification' => $this->notification->toArray(),
            'data' => $this->data
        ];

        if ($this->additional) {
            if (isset($array['data'])) {
                $array['data'] = array_merge($array['data'], $this->additional);
            } else {
                $array['data'] = $this->additional;
            }
        }

        return $array;
    }
}