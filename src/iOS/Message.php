<?php
namespace abraovic\PHPush\iOS;

use abraovic\PHPush;

/**
 *     Copyright 2016
 *
 *     @author Ante Braović - abraovic@gmail.com - antebraovic.me
 *
 *     For more documentation:
 *     @see: https://developer.apple.com/library/ios/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/Chapters/TheNotificationPayload.html
 */

class Message implements PHPush\Message
{
    /** @var Aps $aps */
    private $aps;
    /** @var Alert $alert */
    private $alert;
    private $additional = [];

    /**
     * @param string $title
     */
    function __construct(string $title)
    {
        $this->alert = new Alert($title);
        $this->aps = new Aps($this->alert);
    }

    public function getMessage(): PHPush\Message
    {
        return $this;
    }

    public function setBody(string $title): PHPush\Message
    {
        $this->alert->setTitle($title);
        return $this;
    }

    public function setBadge(int $badge): PHPush\Message
    {
        $this->aps->setBadge($badge);
        return $this;
    }

    public function setAdditional(array $data): void
    {
        $this->additional = $data;
    }

    /**
     * @param string $sound
     * @return Message
     */
    public function setSound(string $sound): Message
    {
        $this->aps->setSound($sound);
        return $this;
    }

    /**
     * @param string $contentAvailable
     * @return Message
     */
    public function setContentAvailable(string $contentAvailable): Message
    {
        $this->aps->setContentAvailable($contentAvailable);
        return $this;
    }

    /**
     * @param string $category
     * @return Message
     */
    public function setCategory(string $category): Message
    {
        $this->aps->setCategory($category);
        return $this;
    }

    /**
     * @param string $launchImage
     * @return Message
     */
    public function setLaunchImage(string $launchImage): Message
    {
        $this->alert->setLaunchImage($launchImage);
        return $this;
    }

    /**
     * @param array $locArgs
     * @return Message
     */
    public function setLocArgs(array $locArgs): Message
    {
        $this->alert->setLocArgs($locArgs);
        return $this;
    }

    /**
     * @param string $locKey
     * @return Message
     */
    public function setLocKey(string $locKey): Message
    {
        $this->alert->setLocKey($locKey);
        return $this;
    }

    /**
     * @param string $actionLocKey
     * @return Message
     */
    public function setActionLocKey(string $actionLocKey): Message
    {
        $this->alert->setActionLocKey($actionLocKey);
        return $this;
    }

    /**
     * @param array $titleLocArgs
     * @return Message
     */
    public function setTitleLocArgs(array $titleLocArgs): Message
    {
        $this->alert->setTitleLocArgs($titleLocArgs);
        return $this;
    }

    /**
     * @param string $titleLocKey
     * @return Message
     */
    public function setTitleLocKey(string $titleLocKey): Message
    {
        $this->setTitleLocKey($titleLocKey);
        return $this;
    }

    public function toArray(): array
    {
        $rsp = ["aps" => $this->aps->toArray()];
        if ($this->additional) {
            $rsp = array_merge($rsp, $this->additional);
        }

        return $rsp;
    }
} 