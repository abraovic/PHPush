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

        $gcmData = $message->getMessage()->toArray();

        if(isset($this->timeToLive)){
            $gcmData['time_to_live'] = $this->timeToLive;
        }

        if(isset($this->restrictedPackageName)){
            $gcmData['restricted_package_name'] = $this->restrictedPackageName;
        }

        if (is_array($this->deviceToken)) {
            $parameters = array(
                'registration_ids' => $this->deviceToken,
            );
        } else {
            $parameters = array(
                'to' => $this->deviceToken,
            );
        }

        $parameters = array_merge($parameters, $gcmData);

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
        $payload = json_encode($parameters);

        if (PHPush\Push\Push::$printPayload) {
            var_dump($payload);
        }

        curl_setopt($ch, CURLOPT_URL, $this->settings['android']['socket_url']['production']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        $result = curl_exec($ch);
        curl_close($ch);
        $this->checkGoogleErrorResponse($result);

        if ($result === FALSE){
            throw new PHPushException(
                '[Android]: Message delivery to third-party service failed!',
                500
            );
        }

        return true;
    }

    /**
     * Parse error response from GCM
     * @param $result
     * @return bool
     * @throws PHPushException
     */
    private function checkGoogleErrorResponse($result)
    {
        $jsonArray = json_decode($result);

        if($jsonArray->canonical_ids != 0 || $jsonArray->failure != 0){
            if(!empty($jsonArray->results))
            {
                for($i = 0 ; $i<count($jsonArray->results) ; $i++){
                    $result = $jsonArray->results[$i];
                    if(isset($result->message_id) && isset($result->registration_id))
                    {
                        // You should replace the original ID with the new value (canonical ID) in your server database
                    }
                    else
                    {
                        if(isset($result->error))
                        {
                            switch ($result->error)
                            {
                                case "NotRegistered":
                                case "InvalidRegistration":
                                    $error_response['rsp'] = 'You should remove the registration ID from your server database';
                                    break;
                                case "Unavailable":
                                case "InternalServerError":
                                    $error_response['rsp'] = 'You could retry to send it late in another request.';
                                    break;
                                case "MissingRegistration":
                                    $error_response['rsp'] = 'Check that the request contains a registration ID';
                                    break;
                                case "InvalidPackageName":
                                    $error_response['rsp'] = 'Make sure the message was addressed to a registration ID whose package name matches the value passed in the request.';
                                    break;
                                case "MismatchSenderId":
                                    $error_response['rsp'] = 'Invalid SENDER_ID';
                                    break;
                                case "MessageTooBig":
                                    $error_response['rsp'] = 'Check that the total size of the payload data included in a message does not exceed 4096 bytes';
                                    break;
                                case "InvalidDataKey":
                                    $error_response['rsp'] = 'Check that the payload data does not contain a key that is used internally by GCM.';
                                    break;
                                case "InvalidTtl":
                                    $error_response['rsp'] = 'Check that the value used in time_to_live is an integer representing a duration in seconds between 0 and 2,419,200.';
                                    break;
                                case "DeviceMessageRateExceed":
                                    $error_response['rsp'] = 'Reduce the number of messages sent to this device';
                                    break;
                                default:
                                    $error_response['rsp'] = 'Unknown error';
                                    break;

                            }

                            throw new PHPushException(
                                "[Android]: APNS error response: [" . $error_response['rsp']. "]",
                                500
                            );
                        }
                    }
                }
            }
        }
    }
}