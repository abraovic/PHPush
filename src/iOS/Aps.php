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
     * @return Aps
     */
    public function setBadge(int $badge): Aps
    {
        $this->badge = $badge;
        return $this;
    }

    /**
     * @param string $category
     * @return Aps
     */
    public function setCategory(string $category): Aps
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @param string $contentAvailable
     * @return Aps
     */
    public function setContentAvailable(string $contentAvailable): Aps
    {
        $this->contentAvailable = $contentAvailable;
        return $this;
    }

    /**
     * @param string $sound
     * @return Aps
     */
    public function setSound(string $sound): Aps
    {
        $this->sound = $sound;
        return $this;
    }

    public function toArray(): array
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