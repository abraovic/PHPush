<?php
namespace abraovic\PHPush\Exception;

/**
 *     Copyright 2016
 *
 *     @author Ante Braović - abraovic@gmail.com - antebraovic.me
 *
 *     Custom PHPush exception handler
 */

class InvalidCredentialsException extends \Exception
{
    public function __construct($message, $code, \Exception $previous = null)
    {
        // make sure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }
} 