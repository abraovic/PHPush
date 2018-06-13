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
     *     @param Message $message
     *     @return bool
     */
    public function sendMessage(Message $message): bool;

    /**
     *     Setup a lifetime of a notification
     *     @param $ttl -> integer value in seconds
     *     @return Push
     *
     *     NOTICE:
     *          This property is differently managed by APNS and FCM. This lib
     *          tries to unify the way of setting this value for both APNS and
     *          FCM. Generally this property is part of message in FCM but a part
     *          of header (or metadata) in APNS.
     */
    public function setNotificationTTL(int $ttl): Push;

    /**
     * Gets current active service
     * @return Push
     * @throws PHPushException
     */
    public function getService(): Push;

    /**
     *     Checks if payload is in a proper size
     *
     *     @param Message $message
     *     @return bool
     */
    public function checkPayload(Message $message): bool ;
} 