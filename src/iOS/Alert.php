<?php
namespace abraovic\PHPush\iOS;

/**
 *     Copyright 2016
 *
 *     Licensed under the Apache License, Version 2.0 (the "License");
 *     you may not use this file except in compliance with the License.
 *     You may obtain a copy of the License at
 *
 *         http://www.apache.org/licenses/LICENSE-2.0
 *
 *     @author Ante Braović - abraovic@gmail.com - antebraovic.me
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
    function __construct($body)
    {
        $this->body = $body;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @param string $launchImage
     * @return $this
     */
    public function setLaunchImage($launchImage)
    {
        $this->launchImage = $launchImage;
        return $this;
    }

    /**
     * @param array $locArgs
     * @return $this
     */
    public function setLocArgs($locArgs)
    {
        $this->locArgs = $locArgs;
        return $this;
    }

    /**
     * @param string $locKey
     * @return $this
     */
    public function setLocKey($locKey)
    {
        $this->locKey = $locKey;
        return $this;
    }

    /**
     * @param string $actionLocKey
     * @return $this
     */
    public function setActionLocKey($actionLocKey)
    {
        $this->actionLocKey = $actionLocKey;
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