<?php
namespace abraovic\PHPush\Android;

use abraovic\PHPush;

/**
 *     Copyright 2016
 *
 *     @author Ante Braović - abraovic@gmail.com - antebraovic.me
 *
 *     For more documentation:
 *     @see: https://firebase.google.com/docs/cloud-messaging/http-server-ref#downstream-http-messages-json
 *           Table 1. Targets, options, and payload for downstream HTTP messages (JSON).
 */

class Message implements PHPush\Message
{
    // priority types are limited to only two options so let those be selectable
    const FCM_MSG_PRIORITY_NORMAL = "normal";
    const FCM_MSG_PRIORITY_HIGH = "high";

    /** @var Notification $notification */
    private $notification;
    private $data = [];
    private $delayWithIdle;
    private $dryRun;
    private $collapseKey;
    private $additional;
    private $priority = self::FCM_MSG_PRIORITY_NORMAL;

    /**
     * @param string $title
     */
    function __construct(string $title)
    {
        $this->notification = new Notification($title);
    }

    public function getMessage(): PHPush\Message
    {
        return $this;
    }

    public function setBody(string $body): PHPush\Message
    {
        $this->notification->setBody($body);
        return $this;
    }

    public function setBadge(int $badge): PHPush\Message
    {
        $this->data['badge'] = $badge;
        $this->notification->setBadge($badge);
        return $this;
    }

    public function setAdditional(array $data): void
    {
        $this->additional = $data;
    }

    /**
     * @param string $collapseKey
     * @return Message
     */
    public function setCollapseKey(string $collapseKey): Message
    {
        $this->collapseKey = $collapseKey;
        return $this;
    }

    /**
     * @param int $delayWithIdle
     * @return Message
     */
    public function setDelayWithIdle(int $delayWithIdle): Message
    {
        $this->delayWithIdle = $delayWithIdle;
        return $this;
    }

    /**
     * @param bool $dryRun
     * @return Message
     */
    public function setDryRun(bool $dryRun): Message
    {
        $this->dryRun = $dryRun;
        return $this;
    }

    /**
     * @param string $icon
     * @return Message
     */
    public function setIcon(string $icon): Message
    {
        $this->notification->setIcon($icon);
        return $this;
    }

    /**
     * @param array $bodyLocArgs
     * @return Message
     */
    public function setBodyLocArgs(array $bodyLocArgs): Message
    {
        $this->notification->setBodyLocArgs($bodyLocArgs);
        return $this;
    }

    /**
     * @param string $bodyLocKey
     * @return Message
     */
    public function setBodyLocKey(string $bodyLocKey): Message
    {
        $this->notification->setBodyLocKey($bodyLocKey);
        return $this;
    }

    /**
     * @param string $clickAction
     * @return Message
     */
    public function setClickAction(string $clickAction): Message
    {
        $this->notification->setClickAction($clickAction);
        return $this;
    }

    /**
     * @param string $color
     * @return Message
     */
    public function setColor(string $color): Message
    {
        $this->notification->setColor($color);
        return $this;
    }

    /**
     * @param string $sound
     * @return Message
     */
    public function setSound(string $sound): Message
    {
        $this->notification->setSound($sound);
        return $this;
    }

    /**
     * @param string $tag
     * @return Message
     */
    public function setTag(string $tag): Message
    {
        $this->notification->setTag($tag);
        return $this;
    }

    /**
     * @param array $titleLocArgs
     * @return Message
     */
    public function setTitleLocArgs(array $titleLocArgs): Message
    {
        $this->notification->setTitleLocArgs($titleLocArgs);
        return $this;
    }

    /**
     * @param string $titleLocKey
     * @return Message
     */
    public function setTitleLocKey(string $titleLocKey): Message
    {
        $this->notification->setTitleLocKey($titleLocKey);
        return $this;
    }

    /**
     * @return string
     */
    public function getPriority(): string
    {
        return $this->priority;
    }

    /**
     * @param string $priority
     */
    public function setPriority(string $priority): void
    {
        $this->priority = $priority;
    }

    public function toArray(): array
    {
        $array = [
            'notification' => $this->notification->toArray(),
            'data' => $this->data,
            'collapse_key' => $this->collapseKey
        ];

        if ($this->additional) {
            if (isset($array['data'])) {
                $array['data'] = array_merge($array['data'], $this->additional);
            } else {
                $array['data'] = $this->additional;
            }
        }

        return $array;
    }
}