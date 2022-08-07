<?php

namespace app\core\validation;

use app\core\db\ActiveRecord;
use Attribute;
use Yii;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Exist extends PropertyValidator
{
    /**
     * @param string $model Model class from which the foreign key is expected.
     * @param string $column (Optional) Foreign column. If no one is provided, the name will
     * be the same as the property being validated.
     * @param bool $multiple (Optional) Wether multiple values should be accepted.
     */
    public function __construct(private string $model, private ?string $column = null, private bool $multiple = false, string $message = null, array|string $on = [])
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
        return $this->message ?? 'Registro não encontrado.';
    }

    /**
     * {@inheritdoc}
     */
    public function validate(): bool
    {
        $propertyValue = $this->getPropertyValue();
        $values = is_string($propertyValue) ? explode(',', $propertyValue) : $propertyValue;

        if (!is_array($values)) {
            throw new \Exception(static::class . ' validator only accepts an array or a string with comma-separated items. Type found: ' . gettype($values));
        }

        $count = count($values);

        if (!$this->multiple && $count > 1) {
            throw new \Exception('More than one value given. To accept multiple values, set ' . static::class . ' validator option "multiple" to "true".');
        }

        /** @var ActiveRecord */
        $relationModel = Yii::createObject($this->model);
        $column = $this->column ?? $this->propertyName;

        return $relationModel::find()->where([$column => $values])->count() === $count;
    }
}
