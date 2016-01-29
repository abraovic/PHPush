<?php
namespace abraovic\PHPush\iOS;

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
 *     @see: https://developer.apple.com/library/ios/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/Chapters/ApplePushService.html
 *           Table 3-1  Keys and values of the aps dictionary
 */

class Message implements PHPush\Message
{
    private $alert;
    private $badge;
    private $sound;
    private $contentAvailable;
    private $additionalProperties;

    public function getMessage()
    {
        $message = array();
        if(isset($this->alert)){
            $message['alert'] = $this->alert;
        }else{
            throw new PHPushException(
                '[iOS]: You must set alert property',
                500
            );
        }
        if(isset($this->badge)){
            $message['badge'] = $this->badge;
        }
        if(isset($this->sound)){
            $message['sound'] = $this->sound;
        }
        if(isset($this->contentAvailable)){
            $message['content-available'] = $this->contentAvailable;
        }
        $return = array('aps' => $message);
        if(isset($this->additionalProperties)){
            $return = array_merge($return, $this->additionalProperties);
        }
        return $return;
    }
    /**
     *      @param mixed $alert
     *
     *      Table 3-2  Child properties of the alert property
     */
    public function setAlert($alert)
    {
        $this->alert = $alert;
    }
    /**
     * @param int $badge
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;
    }
    /**
     * @param string $sound
     */
    public function setSound($sound)
    {
        $this->sound = $sound;
    }
    /**
     * @param mixed $contentAvailable
     */
    public function setContentAvailable($contentAvailable)
    {
        $this->contentAvailable = $contentAvailable;
    }
    /**
     * @param array $additionalProperties
     */
    public function setAdditionalProperties($additionalProperties)
    {
        $this->additionalProperties = $additionalProperties;
    }
} 