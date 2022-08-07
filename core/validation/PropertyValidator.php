<?php

namespace app\core\validation;

use app\core\exceptions\AttributeValidationException;
use ReflectionClass;
use yii\base\Model;

abstract class PropertyValidator implements IValidator
{
    protected ?object $object;
    protected ?string $propertyName;

    /**
     * Creates a new validator.
     * 
     * @param array|string $on Scenarios.
     */
    public function __construct(protected ?string $message = null, protected array|string $on = [])
    {
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getErrorMessage(): string;

    /**
     * {@inheritdoc}
     */
    abstract public function validate(): bool;

    /**
     * {@inheritdoc}
     */
    public function validateObject(object $object, string $propertyName): void
    {
        $isModel = $object instanceof Model;
    
        if ($isModel && !$this->validateScenario($object)) {
            return;
        }

        $this->object = $object;
        $this->propertyName = $propertyName;

        if (($this->isValueEmpty() && !$this->isRequired()) || $this->validate()) {
            return;
        }

        if ($isModel) {
            $object->addError($propertyName, $this->getErrorMessage());
        } else {
            throw new AttributeValidationException($propertyName, $this->getErrorMessage());
        }
    }

    /**
     * Returns the value of the property being validated.
     * 
     * @return mixed Property value.
     */
    protected function getPropertyValue(): mixed
    {
        return $this->object->{$this->propertyName};
    }

    /**
     * Checks if model scenario is compatible with the selected one(s).
     * 
     * @param Model $model Model being validated.
     * 
     * @return bool Whether the scenario requires validation.
     */
    private function validateScenario(Model $model): bool
    {
        if (!$this->on || !$scenarios = is_array($this->on) ? $this->on : [$this->on]) {
            return true;
        }

        return in_array($model->scenario, $scenarios);
    }

    private function isValueEmpty(): bool
    {
        $value = $this->getPropertyValue();

        return !$value && $value !== false && $value !== 0;
    }

    private function isRequired(): bool
    {
        $reflectorClass = new ReflectionClass($this->object::class);
        $property = $reflectorClass->getProperty($this->propertyName);

        return !!$property->getAttributes(Required::class);
    }
}
