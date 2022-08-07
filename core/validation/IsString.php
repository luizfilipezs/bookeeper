<?php

namespace app\core\validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class IsString extends PropertyValidator
{
    /**
     * {@inheritdoc}
     */
    public function __construct(string $message = null, array|string $on = [])
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
        return $this->message ?? 'Apenas caracteres são aceitos.';
    }
    
    /**
     * {@inheritdoc}
     */
    public function validate(): bool
    {
        return is_string($this->getPropertyValue());
    }
}
