<?php

namespace app\core\validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class CallbackMethod extends PropertyValidator
{
    /**
     * @param string $methodName Name of the callback method.
     */
    public function __construct(private string $methodName, string $message = null, array|string $on = [])
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
        return $this->object->{$this->methodName}($this->propertyName) ?? true;
    }
}
