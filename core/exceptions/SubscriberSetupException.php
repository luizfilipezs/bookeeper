<?php

namespace app\core\exceptions;

class SubscriberSetupException extends \Exception
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }
}
