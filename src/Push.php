<?php
namespace abraovic\PHPush;

use abraovic\PHPush\Exception\PHPushException;

/**
 *     Copyright 2016
 *
 *     @author Ante Braović - abraovic@gmail.com - antebraovic.me
 */

interface Push
{
    /**
     *     Send message to required provider
     *     @param Message $message -> object of a type message
     *     @return true on success
     */
    public function sendMessage(Message $message);

    /**
     *     Setup a lifetime of a notification
     *     @param $ttl -> integer value in seconds
     *     @return $this
     *
     *     NOTICE:
     *          This property is differently managed by APNS and FCM. This lib
     *          tries to unify the way of setting this value for both APNS and
     *          FCM. Generally this property is part of message in FCM but a part
     *          of header (or metadata) in APNS.
     */
    public function setNotificationTTL($ttl);

    /**
     * Gets current active service
     * @return $this
     * @throws PHPushException
     */
    public function getService();

    /**
     *     Checks if payload is in a proper size
     *
     *     @param Message $message
     *     @return bool
     */
    public function checkPayload(Message $message);
} 