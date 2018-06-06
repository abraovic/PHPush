<?php
namespace abraovic\PHPush\Android;

/**
 *     Copyright 2016
 *
 *     @author Ante Braović - abraovic@gmail.com - antebraovic.me
 *
 *     For more documentation:
 *     @see: https://firebase.google.com/docs/cloud-messaging/http-server-ref#notification-payload-support
 *           Table 2b. Android — keys for notification messages
 */

class Notification
{
    private $body;
    private $title;
    private $icon;
    private $sound;
    private $badge;
    private $tag;
    private $color;
    private $clickAction;
    private $bodyLocKey;
    private $bodyLocArgs;
    private $titleLocKey;
    private $titleLocArgs;

    /**
     * @param string $title
     */
    function __construct(string $title)
    {
        $this->title = $title;
    }

    /**
     * @param string $body
     * @return Notification
     */
    public function setBody(string $body): Notification
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param string $icon
     * @return Notification
     */
    public function setIcon(string $icon): Notification
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * @param int $badge
     * @return Notification
     */
    public function setBadge(int $badge): Notification
    {
        $this->badge = $badge;
        return $this;
    }

    /**
     * @param array $bodyLocArgs
     * @return Notification
     */
    public function setBodyLocArgs(array $bodyLocArgs): Notification
    {
        $this->bodyLocArgs = $bodyLocArgs;
        return $this;
    }

    /**
     * @param string $bodyLocKey
     * @return Notification
     */
    public function setBodyLocKey(string $bodyLocKey): Notification
    {
        $this->bodyLocKey = $bodyLocKey;
        return $this;
    }

    /**
     * @param string $clickAction
     * @return Notification
     */
    public function setClickAction(string $clickAction): Notification
    {
        $this->clickAction = $clickAction;
        return $this;
    }

    /**
     * @param string $color
     * @return Notification
     */
    public function setColor(string $color): Notification
    {
        $this->color = $color;
        return $this;
    }

    /**
     * @param string $sound
     * @return Notification
     */
    public function setSound(string $sound): Notification
    {
        $this->sound = $sound;
        return $this;
    }

    /**
     * @param string $tag
     * @return Notification
     */
    public function setTag(string $tag): Notification
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @param array $titleLocArgs
     * @return Notification
     */
    public function setTitleLocArgs(array $titleLocArgs): Notification
    {
        $this->titleLocArgs = $titleLocArgs;
        return $this;
    }

    /**
     * @param string $titleLocKey
     * @return Notification
     */
    public function setTitleLocKey(string $titleLocKey): Notification
    {
        $this->titleLocKey = $titleLocKey;
        return $this;
    }

    public function toArray(): array
    {
        $array = ['title' => $this->title];

        if ($this->body) {
            $array['body'] = $this->body;
        }
        if ($this->icon) {
            $array['icon'] = $this->icon;
        }

        return $array;
    }
} 