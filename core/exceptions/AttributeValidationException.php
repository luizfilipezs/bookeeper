<?php

namespace app\core\exceptions;

class AttributeValidationException extends \Exception
{
    public function __construct(public string $propertyName, string $message)
    {
        parent::__construct($message);
    }
}
