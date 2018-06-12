<?php
namespace abraovic\PHPush\iOS;

use abraovic\PHPush;
use abraovic\PHPush\Exception\PHPushException;

/**
 *     Copyright 2016
 *
 *     @author Ante Braović - abraovic@gmail.com - antebraovic.me
 *
 *     This class is used to send a notification to Apple Push Notification
 *     Service (APNS) server.
 */

class APNS_JWT implements PHPush\Push
{
    private $ch;

    private $deviceToken;
    private $settings;
    private $url;
    /** @var JWT $jwt */
    private $jwt;

    private $appBundleId;
    private $timeToLive;
    private $identifier;
    private $priority;
    private $collapseKey;

    private static $port = 443;

    /**
     *     @param $deviceToken
     *     @param string $appBundleId
     *     @param $settings
     *     @param bool $development
     */
    function __construct(
        $deviceToken,
        string $appBundleId,
        JWT $jwt,
        array $settings,
        bool $development = false
    ) {
        $this->deviceToken = $deviceToken;
        $this->settings = $settings;
        $this->appBundleId = $appBundleId;

        // determine proper url
        $this->url = $this->settings['ios_jwt']['socket_url']['production'];
        if ($development) {
            $this->url = $this->settings['ios_jwt']['socket_url']['development'];
        }
        $this->url = 'https://' . $this->url . '/3/device/';

        $this->jwt = $jwt;
    }

    /**
     * @param PHPush\Message $message
     * @return bool
     * @throws PHPushException
     */
    public function sendMessage(PHPush\Message $message): bool
    {
        // Method getMessage has toArray behaviour. To manage
        // data easily let send them as an array to execute
        // method
        return $this->execute($message->getMessage()->toArray());
    }

    public function getService(): PHPush\Push
    {
        return $this;
    }

    public function setNotificationTTL(int $ttl): PHPush\Push
    {
        $this->timeToLive = $ttl;
        return $this;
    }

    /**
     * Setup a identifier of a notification
     * @param string $identifier
     * @return APNS_JWT
     */
    public function setIdentifier(string $identifier): APNS_JWT
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * Setup a priority of a notification
     * @param int $priority
     * @return APNS_JWT
     * @throws PHPushException
     */
    public function setPriority(int $priority): APNS_JWT
    {
        if ($priority != 5 and $priority != 10) {
            throw new PHPushException(
                '[iOS]: Priority can be either 5 or 10!. Read docs at link: https://apple.co/2kRHXgq',
                500
            );
        }
        $this->priority = $priority;
        return $this;
    }

    /**
     * @param string $collapseKey
     * @return APNS_JWT
     * @throws PHPushException
     */
    public function setCollapseKey(string $collapseKey): APNS_JWT
    {
        if(strlen($collapseKey) > 64) {
            throw new PHPushException(
                '[iOS]: Key too long! Your entire key should not contain more that 64 chars',
                500
            );
        }
        $this->collapseKey = $collapseKey;
        return $this;
    }

    public function checkPayload(PHPush\Message $message): bool
    {
        $payload = json_encode($message->getMessage());
        if(mb_strlen($payload) > 2048) {
            return false;
        }
        return true;
    }

    /**
     * Open connection to APNS
     * @throws PHPushException
     */
    private function connect(): void
    {
        $this->ch = curl_init();
        if(!$this->ch){
            throw new PHPushException(
                '[iOS]: Connection to third-party service failed!',
                500
            );
        }

        curl_setopt_array($this->ch, [
            CURLOPT_HTTPHEADER => $this->buildHeader(),
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
            CURLOPT_PORT => self::$port,
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HEADER => 1
        ]);
    }

    /**
     * Close connection to APNS
     */
    private function disconnect(): void
    {
        if ($this->ch){
            curl_close($this->ch);
        }
    }

    /**
     * @param array $payload
     * @throws PHPushException
     * @return bool
     */
    private function execute(array $message): bool
    {
        $this->connect();

        $message = json_encode($message);
        if (mb_strlen($message) > 4096) {
            throw new PHPushException(
                '[iOS]: Message too long! Your entire message should not contain more that 4096 chars',
                500
            );
        }

        if (is_array($this->deviceToken)) {
            foreach ($this->deviceToken as $token) {
                $this->buildAndSend($message, $token);
            }
        } else {
            $this->buildAndSend($message, $this->deviceToken);
        }

        $this->disconnect();

        return true;
    }

    /**
     * Create binary package and write it to a stream
     * @param string $message
     * @param string $token
     * @throws PHPushException
     */
    private function buildAndSend(string $message, string $token): void
    {
        curl_setopt_array($this->ch, [
            CURLOPT_URL => $this->url . $token,
            CURLOPT_POSTFIELDS => $message
        ]);

        $result = curl_exec($this->ch);

        if ($result === FALSE){
            throw new PHPushException(
                '[iOS]: Message delivery to third-party service failed!',
                500
            );
        }

        $this->checkAppleErrorResponse($result);
    }

    /**
     * Parse error response from APNS
     * @param string $result
     * @throws PHPushException
     */
    private function checkAppleErrorResponse(string $result): void
    {
        var_dump($result);
        // TODO: implement response check
    }

    /**
     * @return array
     * @throws PHPushException
     */
    private function buildHeader(): array
    {
        if (!$this->appBundleId) {
            throw new PHPushException(
                "[iOS]: AppBundleId must be a valid value.",
                500
            );
        }

        $headers = array(
            'apns-topic: ' . $this->appBundleId,
            'Authorization: bearer ' . $this->jwt->getToken()
        );

        if ($this->identifier) {
            $headers['apns-id'] = $this->identifier;
        }

        if ($this->timeToLive) {
            $headers['apns-expiration'] = $this->timeToLive;
        }

        if ($this->priority) {
            $headers['apns-priority'] = $this->priority;
        }

        if ($this->collapseKey) {
            $headers['apns-collapse-id'] = $this->collapseKey;
        }

        return $headers;
    }
}