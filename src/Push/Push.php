<?php
namespace abraovic\PHPush\Push;

use abraovic\PHPush\Exception\PHPushException;
use abraovic\PHPush;
use Symfony\Component\Yaml\Yaml;

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
 */

class Push implements PHPush\Push
{
    // define constants to determine which kind of a push notification
    // should be sent
    const IOS = 'iOS';
    const ANDROID = 'Android';

    /** @var PHPush\Push $service */
    private $service;
    /** @var array $config */
    private $config;

    /**
     * @param $type         ->  defines type of service identified by constants
     *                          example $type = Push::IOS
     * @param $credentials  ->  requires credentials in form of an array
     *                            for iOS:
     *                                  array('device_token' => '...', 'certificate_path' => '...', 'certificate_phrase' => '...', 'dev' => true)
     *                            for Android
     *                                  array('device_token' => '...', 'google_api_key' => '...')
     * @throws PHPushException
     */
    function __construct($type, $credentials)
    {
        $this->config = $this->loadConfiguration();
        $this->validateCredentials($type, $credentials);

        switch ($type) {
            case self::IOS:
                $this->service = new PHPush\iOS\APNS(
                    $credentials['device_token'],
                    $credentials['certificate_path'],
                    $credentials['certificate_phrase'],
                    $this->config,
                    $credentials['dev']
                );
                break;
            case self::ANDROID:
                $this->service = new PHPush\Android\GCM(
                    $credentials['device_token'],
                    $credentials['google_api_key'],
                    $this->config
                );
                break;
            default:
                throw new PHPushException(
                    "Unxeisting service called. Available services are [Push::IOS] and [Push::ANDROID]",
                    500
                );
                break;
        }
    }

    public function getService()
    {
        return $this->service;
    }

    public function sendMessage(PHPush\Message $message)
    {
        return $this->service->sendMessage($message);
    }

    public function setNotificationTTL($ttl)
    {
        return $this->service->setNotificationTTL($ttl);
    }

    public function setIdentifier($identifier)
    {
        return $this->service->setIdentifier($identifier);
    }

    public function checkPayload(PHPush\Message $message)
    {
        $this->service->checkPayload($message);
    }

    /**
     * Loads configuration from config.yml
     *
     * @param $path -> if user would like to keep it somewhere else
     * @return array
     * @throws PHPush\Exception\ConfigMissingException
     */
    private function loadConfiguration($path = "system")
    {
        if ($path == "system") {
            $path = realpath(dirname(__FILE__)) . "/../../config.yml";
        }

        if (!is_file($path)) {
            throw new PHPush\Exception\ConfigMissingException(
                "Configuration file [config.yml] is missing, or path: [" . $path . "] is invalid",
                404
            );
        }

        return Yaml::parse(file_get_contents($path));
    }

    /**
     * Validates access credentials for given service
     * @param $type         -> see __construct
     * @param $credentials  -> see __construct
     * @return bool
     * @throws PHPush\Exception\InvalidCredentialsException
     * @throws PHPushException
     */
    private function validateCredentials($type, $credentials)
    {
        switch ($type) {
            case self::IOS:
                $countOk = 0;
                foreach ($credentials as $key => $item) {
                    if (in_array($key, $this->config['ios']['credentials'])) {
                        $countOk++;
                    }
                }
                if ($countOk == 4) {
                    return true;
                }
                break;
            case self::ANDROID:
                $countOk = 0;
                foreach ($credentials as $key => $item) {
                    if (in_array($key, $this->config['android']['credentials'])) {
                        $countOk++;
                    }
                }
                if ($countOk == 2) {
                    return true;
                }
                break;
            default:
                throw new PHPushException(
                    "Unxeisting service called. Available services are [Push::IOS] and [Push::ANDROID]",
                    500
                );
                break;
        }

        throw new PHPush\Exception\InvalidCredentialsException(
            "You did not provide proper credentials for " . $type . " service",
            401
        );
    }
} 