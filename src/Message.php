<?php
namespace abraovic\PHPush;

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
 */

interface Message
{
    /**
     * Gets message
     */
    public function getMessage();

    /**
     * Set notification title
     * @param string $body
     * @return $this
     */
    public function setBody($body);

    /**
     * Set notification title
     * @param int $badge
     * @return $this
     */
    public function setBadge($badge);

    /**
     * Set notification title
     * @param array $data
     * @return $this
     */
    public function setAdditional($data);
} 