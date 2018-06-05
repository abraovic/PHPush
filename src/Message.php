<?php
namespace abraovic\PHPush;

/**
 *     Copyright 2016
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