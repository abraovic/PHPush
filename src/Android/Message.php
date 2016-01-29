<?php
namespace abraovic\PHPush\Android;

use abraovic\PHPush;
use abraovic\PHPush\Exception\PHPushException;

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
 *     @see: http://developer.android.com/google/gcm/server.html
 *           Table 1. Message parameters
 */

class Message implements PHPush\Message
{
    private $data;
    private $delayWithIdle;
    private $dryRun;

    public function getMessage()
    {
        $message = array();
        if(isset($this->data)){
            $message['field'] = $this->data;
        }else{
            throw new PHPushException(
                '[Android]: You must set data property',
                500
            );
        }
        if(isset($this->delayWithIdle)){
            $message['delay_while_idle'] = $this->delayWithIdle;
        }
        if(isset($this->dryRun)){
            $message['dry_run'] = $this->dryRun;
        }
        return $message;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @param int $delayWithIdle
     */
    public function setDelayWithIdle($delayWithIdle)
    {
        $this->delayWithIdle = $delayWithIdle;
    }

    /**
     * @param boolean $dryRun
     */
    public function setDryRun($dryRun)
    {
        $this->dryRun = $dryRun;
    }
}