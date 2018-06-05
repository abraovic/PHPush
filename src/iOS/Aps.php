<?php
namespace abraovic\PHPush\iOS;

/**
 *     Copyright 2016
 *
 *     @author Ante Braović - abraovic@gmail.com - antebraovic.me
 *
 *     For more documentation:
 *     @see: https://developer.apple.com/library/ios/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/Chapters/TheNotificationPayload.html
 */

class Aps
{
    private $alert;
    private $badge;
    private $sound;
    private $contentAvailable;
    private $category;

    /**
     * @param Alert $alert
     */
    function __construct(Alert $alert)
    {
        $this->alert = $alert;
    }

    /**
     * @param int $badge
     * @return $this
     */
    public function setBadge($badge)
    {
        $this->badge = $badge;
        return $this;
    }

    /**
     * @param string $category
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @param string $contentAvailable
     * @return $this
     */
    public function setContentAvailable($contentAvailable)
    {
        $this->contentAvailable = $contentAvailable;
        return $this;
    }

    /**
     * @param string $sound
     * @return $this
     */
    public function setSound($sound)
    {
        $this->sound = $sound;
        return $this;
    }

    /**
     * @return array $array
     */
    public function toArray()
    {
        $array = ['alert' => $this->alert->toArray()];

        if ($this->badge) {
            $array['badge'] = $this->badge;
        }
        if ($this->sound) {
            $array['sound'] = $this->sound;
        }
        if ($this->contentAvailable) {
            $array['content-available'] = $this->contentAvailable;
        }
        if ($this->category) {
            $array['category'] = $this->category;
        }

        return $array;
    }
} 