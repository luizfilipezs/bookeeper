<?php

namespace app\core\validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class MinLength extends PropertyValidator
{
    /**
     * @param int $value Maximum length.
     */
    public function __construct(public int $value, string $message = null, array|string $on = [])
    {
        parent::__construct(
            message: $message,
            on: $on
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getErrorMessage(): string
    {
        return $this->message ?? 'Use, no mínimo, {$this->value} caracteres.';
    }

    /**
     * {@inheritdoc}
     */
    public function validate(): bool
    {
        return strlen($this->getPropertyValue()) > $this->value;
    }
}
