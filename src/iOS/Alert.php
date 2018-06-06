<?php
namespace abraovic\PHPush\iOS;

/**
 *     Copyright 2016
 *
 *     For more documentation:
 *     @see: https://developer.apple.com/library/ios/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/Chapters/TheNotificationPayload.html
 */

class Alert
{
    private $title;
    private $body;
    private $titleLocKey;
    private $titleLocArgs;
    private $actionLocKey;
    private $locKey;
    private $locArgs;
    private $launchImage;

    /**
     * @param string $body
     */
    function __construct(string $body)
    {
        $this->body = $body;
    }

    /**
     * @param string $title
     * @return Alert
     */
    public function setTitle(string $title): Alert
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $launchImage
     * @return Alert
     */
    public function setLaunchImage(string $launchImage): Alert
    {
        $this->launchImage = $launchImage;
        return $this;
    }

    /**
     * @param array $locArgs
     * @return Alert
     */
    public function setLocArgs(string $locArgs): Alert
    {
        $this->locArgs = $locArgs;
        return $this;
    }

    /**
     * @param string $locKey
     * @return Alert
     */
    public function setLocKey(string $locKey): Alert
    {
        $this->locKey = $locKey;
        return $this;
    }

    /**
     * @param string $actionLocKey
     * @return Alert
     */
    public function setActionLocKey(string $actionLocKey): Alert
    {
        $this->actionLocKey = $actionLocKey;
        return $this;
    }

    /**
     * @param array $titleLocArgs
     * @return Alert
     */
    public function setTitleLocArgs(string $titleLocArgs): Alert
    {
        $this->titleLocArgs = $titleLocArgs;
        return $this;
    }

    /**
     * @param string $titleLocKey
     * @return Alert
     */
    public function setTitleLocKey(string $titleLocKey): Alert
    {
        $this->titleLocKey = $titleLocKey;
        return $this;
    }

    public function toArray(): array
    {
        $array = ['body' => $this->body];

        if ($this->title) {
            $array['title'] = $this->title;
        }
        if ($this->titleLocKey) {
            $array['title-loc-key'] = $this->titleLocKey;
        }
        if ($this->titleLocArgs) {
            $array['title-loc-args'] = $this->titleLocArgs;
        }
        if ($this->actionLocKey) {
            $array['action-loc-key'] = $this->actionLocKey;
        }
        if ($this->locKey) {
            $array['loc-key'] = $this->locKey;
        }
        if ($this->locArgs) {
            $array['loc-args'] = $this->locArgs;
        }
        if ($this->launchImage) {
            $array['launch-image'] = $this->launchImage;
        }

        return $array;
    }
} 