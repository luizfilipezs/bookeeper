<?php

namespace app\core\validation;

/**
 * Implements methods to be used in validator attributes.
 */
interface IValidator
{
    /**
     * Returns the validation error message.
     * 
     * @return string Validation message.
     */
    public function getErrorMessage(): string;

    /**
     * Validates the actual value.
     * 
     * @return bool Validation result.
     */
    public function validate(): bool;

    /**
     * Validates a model attribute, adding an error into it if validation fails.
     * 
     * @param object $model Object to validate.
     * @param string $propertyName Attribute to validate.
     */
    public function validateObject(object $model, string $propertyName): void;
}
