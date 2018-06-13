<?php
namespace abraovic\PHPush\Push;

use abraovic\PHPush\Exception\PHPushException;
use abraovic\PHPush;
use Symfony\Component\Yaml\Yaml;

/**
 *     Copyright 2016
 *
 *     @author Ante Braović - abraovic@gmail.com - antebraovic.me
 *
 */

class Push implements PHPush\Push
{
    // define constants to determine which kind of a push notification
    // should be sent
    const IOS = 'iOS';
    const IOS_JWT = 'iOS_jwt';
    const ANDROID = 'Android';

    private $service;
    /** @var array $config */
    private $config;
    private $type;

    // if it is set as true payload wil bi printed on stdout as var_dump(payload)
    public static $printPayload = false;

    /**
     * @param $type         ->  defines type of service identified by constants
     *                          example $type = Push::IOS
     * @param $credentials  ->  requires credentials in form of an array
     *                            for iOS:
     *                                  array('device_token' => '...', 'certificate_path' => '...', 'certificate_phrase' => '...', 'dev' => true)
     *                            for Android
     *                                  array('device_token' => '...', 'google_api_key' => '...')
     * @throws PHPushException
     * @throws PHPush\Exception\ConfigMissingException
     * @throws PHPush\Exception\InvalidCredentialsException
     */
    function __construct($type, $credentials)
    {
        $this->config = $this->loadConfiguration();
        $this->type = $type;
        $this->validateCredentials($type, $credentials);

        switch ($type) {
            case self::IOS:
                /** @var PHPush\iOS\APNS $service */
                $this->service = new PHPush\iOS\APNS(
                    $credentials['device_token'],
                    $credentials['certificate_path'],
                    $credentials['certificate_phrase'],
                    $this->config,
                    $credentials['dev']
                );
                break;
            case self::IOS_JWT:
                /** @var PHPush\iOS\APNS_JWT $service */
                $this->service = new PHPush\iOS\APNS_JWT(
                    $credentials['device_token'],
                    $credentials['app_bundle_id'],
                    $credentials['jwt'],
                    $this->config,
                    $credentials['dev']
                );
                break;
            case self::ANDROID:
                /** @var PHPush\Android\FCM $service */
                $this->service = new PHPush\Android\FCM(
                    $credentials['device_token'],
                    $credentials['google_api_key'],
                    $this->config
                );
                break;
            default:
                throw new PHPushException(
                    "Non-existing service called. Available services are [Push::IOS] and [Push::ANDROID]",
                    500
                );
                break;
        }
    }

    /**
     * @return PHPush\Push
     * @throws PHPushException
     */
    public function getService(): PHPush\Push
    {
        switch ($this->type) {
            case self::IOS:
                /** @var PHPush\iOS\APNS $svc */
                $svc = $this->service;
                break;
            case self::IOS_JWT:
                /** @var PHPush\iOS\APNS_JWT $svc */
                $svc = $this->service;
                break;
            case self::ANDROID:
                /** @var PHPush\Android\FCM $svc */
                $svc = $this->service;
                break;
            default:
                throw new PHPushException(
                    "Non-existing service called. Available services are [Push::IOS] and [Push::ANDROID]",
                    500
                );
                break;
        }

        return $svc;
    }

    /**
     * @param PHPush\Message $message
     * @return bool
     * @throws PHPushException
     */
    public function sendMessage(PHPush\Message $message): bool
    {
        return $this->service->sendMessage($message);
    }

    /**
     * @param int $ttl
     * @return PHPush\Push
     */
    public function setNotificationTTL(int $ttl): PHPush\Push
    {
        return $this->service->setNotificationTTL($ttl);
    }

    /**
     * @param PHPush\Message $message
     * @return bool
     */
    public function checkPayload(PHPush\Message $message): bool
    {
        return $this->service->checkPayload($message);
    }

    /**
     * Loads configuration from config.yml
     *
     * @param $path -> if user would like to keep it somewhere else
     * @return array
     * @throws PHPush\Exception\ConfigMissingException
     */
    private function loadConfiguration($path = "system"): array
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
    private function validateCredentials($type, $credentials): bool
    {
        if (is_array($credentials['device_token'])) {
            if (count($credentials['device_token']) > 1000) {
                throw new PHPush\Exception\InvalidCredentialsException(
                    "You have exceeded max of 1000 tokens that can be used in a single connection",
                    401
                );
            }
        }

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
            case self::IOS_JWT:
                $countOk = 0;
                foreach ($credentials as $key => $item) {
                    if (in_array($key, $this->config['ios_jwt']['credentials'])) {
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
                    "Non-existing service called. Available services are [Push::IOS] and [Push::ANDROID]",
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