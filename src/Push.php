<?php
namespace abraovic\PHPush;

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
     *          This property is differently managed by APNS and GCM. This lib
     *          tries to unify the way of setting this value for both APNS and
     *          GCM. Generally this property is part of message in GCM but a part
     *          of header (or metadata) in APNS.
     */

    public function setNotificationTTL($ttl);
    /**
     *     Setup a identifier of a notification
     *     @param $identifier -> string value
     *     @return $this
     *
     *     NOTICE:
     *          This property has different meaning in APNS that in GCM. To fully understand
     *          this you should read following docs (or at least important parts):
     *          @see: http://developer.android.com/google/gcm/server.html
     *                -> GCM (Table 1. Message parameters.)
     *          @see: https://developer.apple.com/library/ios/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/Chapters/ApplePushService.html
     *                -> APNS (Table 3-1  Keys and values of the aps dictionary)
     */

    public function setIdentifier($identifier);

    /**
     *     Gets current active service
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