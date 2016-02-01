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
 *     This class is used to send a notification to Google Cloud Messaging
 *     (GCM) server.
 */

class GCM  implements PHPush\Push
{
    private $deviceToken;
    private $googleApiKey;
    private $settings;
    private $timeToLive;
    private $restrictedPackageName;

    /**
     *     @param $deviceToken -> string that represents the device token
     *     @param $googleApiKey
     *     @param $settings
     */
    function __construct(
        $deviceToken,
        $googleApiKey,
        $settings
    ) {
        $this->deviceToken = $deviceToken;
        $this->googleApiKey = $googleApiKey;
        $this->settings = $settings;
    }

    public function sendMessage(PHPush\Message $message)
    {
        $headers = array(
            'Authorization: key=' . $this->googleApiKey,
            'Content-Type: application/json'
        );

        $gcmData = $message->getMessage();

        if(isset($this->timeToLive)){
            $gcmData['time_to_live'] = $this->timeToLive;
        }

        if(isset($this->restrictedPackageName)){
            $gcmData['restricted_package_name'] = $this->restrictedPackageName;
        }

        $parameters = array(
            'registration_ids' => array($this->deviceToken),
            'data' => $gcmData,
        );

        return $this->execute($parameters, $headers);
    }

    public function getService()
    {
        return $this;
    }

    public function setNotificationTTL($ttl)
    {
        $this->timeToLive = $ttl;
        return $this;
    }

    /**
     * Setup a restricted package name
     * @param $restrictedPackageName -> string value
     * @return $this
     * @throws PHPushException
     */
    public function setRestrictedPackageName($restrictedPackageName)
    {
        $this->restrictedPackageName = $restrictedPackageName;
        return $this;
    }

    public function checkPayload(PHPush\Message $message)
    {
        // GCM does not have any limit at the time so it will return true
        return true;
    }

    /**
     *     @param $parameters -> array of data that will be sent to
     *                           GCM server
     *     @param $headers
     *     @throws PHPushException
     *     @return true on success
     */
    private function execute($parameters, $headers)
    {
        $ch = curl_init();
        if(!$ch){
            throw new PHPushException(
                '[Android]: Connection to third-party service failed!',
                500
            );
        }
        curl_setopt($ch, CURLOPT_URL, $this->settings['android']['socket_url']['production']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
        $result = curl_exec($ch);
        curl_close($ch);

        if ($result === FALSE){
            throw new PHPushException(
                '[Android]: Message delivery to third-party service failed!',
                500
            );
        }

        return true;
    }
}