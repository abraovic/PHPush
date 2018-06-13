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
     * @return Message
     */
    public function getMessage(): Message;

    /**
     * Set notification title
     * @param string $body
     * @return Message
     */
    public function setBody(string $body): Message;

    /**
     * Set notification title
     * @param int $badge
     * @return Message
     */
    public function setBadge(int $badge): Message;

    /**
     * Set notification title
     * @param array $data
     */
    public function setAdditional(array $data): void;

    /**
     * To Array
     * @@return array
     */
    public function toArray(): array;
} 