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
 *     This class is used to send a notification to Apple Push Notification
 *     Service (APNS) server.
 */


class APNS implements PHPush\Push
{
    private $deviceToken;
    private $certificatePath;
    private $certificateParaphrase;
    private $settings;
    private $development;
    private $timeToLive;
    private $identifier;
    private $priority;

    /**
     *     @param $deviceToken -> string that represents the device token
     *     @param $certificatePath -> string that loads certificate resource
     *     @param $certificateParaphrase -> string that holds the paraphrase
     *                                      for certificate
     *     @param $settings
     *     @param $development -> if true development url will be used
     */
    function __construct(
        $deviceToken,
        $certificatePath,
        $certificateParaphrase,
        $settings,
        $development = false
    ) {
        $this->deviceToken = $deviceToken;
        $this->certificatePath = $certificatePath;
        $this->certificateParaphrase = $certificateParaphrase;

        $this->settings = $settings;
        $this->development = $development;
    }

    public function sendMessage(PHPush\Message $message)
    {
        // Method getMessage has toArray behaviour. To manage
        // data easily let send them as an array to execute
        // method
        return $this->execute($message->getMessage());
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

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    public function checkPayload(PHPush\Message $message)
    {
        $payload = json_encode($message->getMessage());
        if(mb_strlen($payload) > 250) {
            return false;
        }
        return true;
    }

    /**
     *     @param $parameters -> array of data that will be sent to
     *                           APNS server
     *     @throws PHPushException
     *     @return true on success
     */
    private function execute($parameters)
    {
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->certificatePath);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->certificateParaphrase);
        $socketUrl = $this->settings['ios']['socket_url']['production'];
        if ($this->development) {
            $socketUrl = $this->settings['ios']['socket_url']['development'];
        }

        $fp = stream_socket_client($socketUrl, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp) {
            throw new PHPushException(
                '[iOS]: Connection to third-party service failed!',
                500
            );
        }
        $payload = json_encode($parameters);
        if (mb_strlen($payload) > 250) {
            throw new PHPushException(
                '[iOS]: Message too long! Your entire payload should  not contain more that 250 chars',
                500
            );
        }

        /// bulid message
        $msg =  chr(0) . pack('n', 32) . pack('H*', $this->deviceToken) . pack('n', strlen($payload)) . $payload;
        if(isset($this->identifier)){
            $msg .= pack("N", $this->identifier);
        }else{
            $msg .= pack("C", 1).pack("C", 1).pack("C", 1).pack("C", 1);
        }
        if(isset($this->expiry)){
            $msg .= pack("N", $this->expiry);
        }else{
            $msg .= pack("N", time()+(90 * 24 * 60 * 60));  // 90 days default
        }
        if(isset($this->priority)){
            $msg .= pack("N", $this->priority);
        }
        $result = fwrite($fp, $msg, strlen($msg));
        $this->checkAppleErrorResponse($fp);
        fclose($fp);

        if (!$result) {
            throw new PHPushException(
                '[iOS]: Message delivery to third-party service failed!',
                500
            );
        }

        return true;
    }

    /**
     * Parse error response from APNS
     * @param $fp
     * @return bool
     * @throws PHPushException
     */
    private function checkAppleErrorResponse($fp) {

        $apple_error_response = fread($fp, 6);

        if ($apple_error_response) {

            $error_response = unpack('Ccommand/Cstatus_code/Nidentifier', $apple_error_response);

            if ($error_response['status_code'] == '0') {
                $error_response['status_code'] = '0-No errors encountered';

            } else if ($error_response['status_code'] == '1') {
                $error_response['status_code'] = '1-Processing error';

            } else if ($error_response['status_code'] == '2') {
                $error_response['status_code'] = '2-Missing device token';

            } else if ($error_response['status_code'] == '3') {
                $error_response['status_code'] = '3-Missing topic';

            } else if ($error_response['status_code'] == '4') {
                $error_response['status_code'] = '4-Missing payload';

            } else if ($error_response['status_code'] == '5') {
                $error_response['status_code'] = '5-Invalid token size';

            } else if ($error_response['status_code'] == '6') {
                $error_response['status_code'] = '6-Invalid topic size';

            } else if ($error_response['status_code'] == '7') {
                $error_response['status_code'] = '7-Invalid payload size';

            } else if ($error_response['status_code'] == '8') {
                $error_response['status_code'] = '8-Invalid token';

            } else if ($error_response['status_code'] == '255') {
                $error_response['status_code'] = '255-None (unknown)';

            } else {
                $error_response['status_code'] = $error_response['status_code'].'-Not listed';
            }

            throw new PHPushException(
                "[iOS]: APNS error response: [" . $error_response['command']. "] with identifier: [" . $error_response['identifier'] . "] and status: [" . $error_response['status_code'] . "]",
                500
            );
        }
    }
}
