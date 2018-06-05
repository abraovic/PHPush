<?php
namespace abraovic\PHPush\iOS;

use abraovic\PHPush;
use abraovic\PHPush\Exception\PHPushException;

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
    function __construct($title)
    {
        $this->alert = new Alert($title);
        $this->aps = new Aps($this->alert);
    }

    public function getMessage()
    {
        return $this;
    }

    public function setBody($title)
    {
        $this->alert->setTitle($title);
        return $this;
    }

    public function setBadge($badge)
    {
        $this->aps->setBadge($badge);
        return $this;
    }

    public function setAdditional($data)
    {
        $this->additional = $data;
        return $this;
    }

    /**
     * @param string $sound
     * @return $this
     */
    public function setSound($sound)
    {
        $this->aps->setSound($sound);
        return $this;
    }

    /**
     * @param string $contentAvailable
     * @return $this
     */
    public function setContentAvailable($contentAvailable)
    {
        $this->aps->setContentAvailable($contentAvailable);
        return $this;
    }

    /**
     * @param string $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->aps->setCategory($category);
        return $this;
    }

    /**
     * @param string $launchImage
     * @return $this
     */
    public function setLaunchImage($launchImage)
    {
        $this->alert->setLaunchImage($launchImage);
        return $this;
    }

    /**
     * @param array $locArgs
     * @return $this
     */
    public function setLocArgs($locArgs)
    {
        $this->alert->setLocArgs($locArgs);
        return $this;
    }

    /**
     * @param string $locKey
     * @return $this
     */
    public function setLocKey($locKey)
    {
        $this->alert->setLocKey($locKey);
        return $this;
    }

    /**
     * @param string $actionLocKey
     * @return $this
     */
    public function setActionLocKey($actionLocKey)
    {
        $this->alert->setActionLocKey($actionLocKey);
        return $this;
    }

    /**
     * @param array $titleLocArgs
     * @return $this
     */
    public function setTitleLocArgs($titleLocArgs)
    {
        $this->alert->setTitleLocArgs($titleLocArgs);
        return $this;
    }

    /**
     * @param string $titleLocKey
     * @return $this
     */
    public function setTitleLocKey($titleLocKey)
    {
        $this->setTitleLocKey($titleLocKey);
        return $this;
    }

    public function toArray()
    {
        $rsp = ["aps" => $this->aps->toArray()];
        if ($this->additional) {
            $rsp = array_merge($rsp, $this->additional);
        }

        return $rsp;
    }
} 