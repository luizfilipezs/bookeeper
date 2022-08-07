<?php

namespace app\core\validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Required extends PropertyValidator
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
        return $this->message ?? 'Campo obrigatório.';
    }
    
    /**
     * {@inheritdoc}
     */
    public function validate(): bool
    {
        return !!$this->getPropertyValue();
    }
}
