<?php

namespace app\core\exceptions;

/**
 * Thrown when the message is intended to be read by end users.
 */
class FriendlyException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
