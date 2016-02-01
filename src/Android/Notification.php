<?php
namespace abraovic\PHPush\Android;

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
 *     @see: https://developers.google.com/cloud-messaging/concept-options#notifications_and_data_messages
 */

class Notification
{
    private $body;
    private $title;
    private $icon;

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