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
    const SOCKET_SELECT_TIMEOUT = 1000000;

    private $socketPointer;
    private $deviceToken;
    private $certificatePath;
    private $certificatePassphrase;
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
        $this->certificatePassphrase = $certificateParaphrase;

        $this->settings = $settings;
        $this->development = $development;
    }

    public function sendMessage(PHPush\Message $message)
    {
        // Method getMessage has toArray behaviour. To manage
        // data easily let send them as an array to execute
        // method
        return $this->execute($message->getMessage()->toArray());
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
     * Setup a identifier of a notification
     * @param $identifier -> string value
     * @return $this
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * Setup a priority of a notification
     * @param $priority
     * @return $this
     */
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
     * Open connection to APNS
     */
    private function connect()
    {
        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $this->certificatePath);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $this->certificatePassphrase);
        $socketUrl = $this->settings['ios']['socket_url']['production'];
        if ($this->development) {
            $socketUrl = $this->settings['ios']['socket_url']['development'];
        }

        try {
            $this->socketPointer = stream_socket_client($socketUrl, $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
            if (!$this->socketPointer) {
                throw new PHPushException(
                    '[iOS]: Connection to third-party service failed!',
                    500
                );
            }
        } catch (\Exception $e) {
            throw new PHPushException(
                '[iOS]: Connection to third-party service failed! - Exception message: ' . $e->getMessage() ,
                500
            );
        }
    }

    /**
     * Close connection to APNS
     */
    public function disconnect()
    {
        fclose($this->socketPointer);
    }

    /**
     *     @param $parameters -> array of data that will be sent to
     *                           APNS server
     *     @throws PHPushException
     *     @return true on success
     */
    private function execute($parameters)
    {
        $this->connect();

        $payload = json_encode($parameters);
        if (mb_strlen($payload) > 250) {
            throw new PHPushException(
                '[iOS]: Message too long! Your entire payload should  not contain more that 250 chars',
                500
            );
        }

        $result = false;
        if (is_array($this->deviceToken)) {
            $counter = 0;
            while (count($this->deviceToken) > 0) {
                $result = $this->buildAndSend(
                    $this->deviceToken[$counter],
                    $payload
                );
                $this->skipOnFailed($counter);

                $counter++;
            }
        } else {
            $result = $this->buildAndSend($this->deviceToken, $payload);
        }

        $this->disconnect();

        if (!$result) {
            throw new PHPushException(
                '[iOS]: Message delivery to third-party service failed!',
                500
            );
        }

        return true;
    }

    /**
     * Create binary package and write it to a stream
     * @param $token -> device token
     * @param $payload -> device payload
     *
     * @return true
     */
    private function buildAndSend($token, $payload)
    {
        /// bulid message
        $msg =  chr(0) . pack('n', 32) . pack('H*', $token) . pack('n', strlen($payload)) . $payload;
        if (isset($this->identifier)) {
            $msg .= pack("N", $this->identifier);
        } else {
            $msg .= pack("C", 1).pack("C", 1).pack("C", 1).pack("C", 1);
        }
        if (isset($this->timeToLive)) {
            $msg .= pack("N", $this->timeToLive);
        } else {
            $msg .= pack("N", time()+(90 * 24 * 60 * 60));  // 90 days default
        }
        if (isset($this->priority)) {
            $msg .= pack("N", $this->priority);
        }
        $result = fwrite($this->socketPointer, $msg);
        $this->checkAppleErrorResponse($this->socketPointer);

        return $result;
    }

    /**
     * Check stream and re-connect to APNS if need
     * @param $position
     */
    private function skipOnFailed($position)
    {
        $read = [$this->socketPointer];
        $null = NULL;
        $changedStream = @stream_select($read, $null, $null, 0, self::SOCKET_SELECT_TIMEOUT);

        // if stream select is > 1 that means there was an issue with writing
        // to APNS socket which means we need to re-establish socket connection
        // and continue sending rest of notifications
        if ($changedStream > 0) {
            $this->disconnect();
            $this->connect();
        }

        // remove token from the list after it has been used
        unset($this->deviceToken[$position]);
    }

    /**
     * Parse error response from APNS
     * @param $fp
     * @return bool
     * @throws PHPushException
     */
    private function checkAppleErrorResponse($fp)
    {
        stream_set_blocking($fp, 0);
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
