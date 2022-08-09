<?php

namespace app\core\validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class CallbackMethod extends PropertyValidator
{
    /**
     * @param string $methodName (Optional) Name of the callback method. If none is provided,
     * "validatePropertyName" will be used instead. ("PropertyName" is the capitalized
     * name of the property being validated.)
     * 
     * In the example below, the callback method will be "validatePassword":
     * 
     * ```php
     * #[CallbackMethod]
     * public $password;
     * ```
     */
    public function __construct(private ?string $methodName = null, string $message = null, array|string $on = [])
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
        return $this->object->{$this->getMethodName()}($this->propertyName) ?? true;
    }

    /**
     * Returns the name of the callback method.
     * 
     * @return string Name defined on contructor or "validate{PropertyName}".
     */
    private function getMethodName(): string
    {
        return $this->methodName ?? 'validate' . ucfirst($this->propertyName);
    }
}
