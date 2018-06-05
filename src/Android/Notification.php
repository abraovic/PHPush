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
    function __construct($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * @param string $icon
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
        return $this;
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
     * @param array $bodyLocArgs
     * @return $this
     */
    public function setBodyLocArgs($bodyLocArgs)
    {
        $this->bodyLocArgs = $bodyLocArgs;
        return $this;
    }

    /**
     * @param string $bodyLocKey
     * @return $this
     */
    public function setBodyLocKey($bodyLocKey)
    {
        $this->bodyLocKey = $bodyLocKey;
        return $this;
    }

    /**
     * @param string $clickAction
     * @return $this
     */
    public function setClickAction($clickAction)
    {
        $this->clickAction = $clickAction;
        return $this;
    }

    /**
     * @param string $color
     * @return $this
     */
    public function setColor($color)
    {
        $this->color = $color;
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
     * @param string $tag
     * @return $this
     */
    public function setTag($tag)
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * @param array $titleLocArgs
     * @return $this
     */
    public function setTitleLocArgs($titleLocArgs)
    {
        $this->titleLocArgs = $titleLocArgs;
        return $this;
    }

    /**
     * @param string $titleLocKey
     * @return $this
     */
    public function setTitleLocKey($titleLocKey)
    {
        $this->titleLocKey = $titleLocKey;
        return $this;
    }

    public function toArray()
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