<?php

namespace app\core\validation;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Boolean extends PropertyValidator
{
    const FROM_INTEGER = 'integer';
    const FROM_STRING = 'string';

    /**
     * @param bool $type (Optional) Boolean type (`true` or `false`).
     * @param string[] $conversions (Optional) Conversion types. They are used to try
     * convert the property value to a boolean.
     */
    public function __construct(private ?bool $type = null, private array $conversions = [], string $message = null, array|string $on = [])
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
        return $this->message ?? 'O valor deve ser um booleano.';
    }
    
    /**
     * {@inheritdoc}
     */
    public function validate(): bool
    {
        $value = $this->getPropertyValue();

        if (!is_bool($value)) {
            if (!$this->canConvert()) {
                return false;
            }

            $this->convert($value);
        }

        return $this->type !== null ? $value === $this->type : true;
    }

    private function canConvert(): bool
    {
        $value = $this->getPropertyValue();

        if ($value === 0 || $value === 1) {
            return $this->canConvertFrom(self::FROM_INTEGER);
        }

        if ($value === '0' || $value === '1') {
            return $this->canConvertFrom(self::FROM_STRING);
        }

        return false;
    }

    private function canConvertFrom(string $conversionType): bool
    {
        return in_array($conversionType, $this->conversions);
    }

    private function convert(mixed &$value)
    {
        return $this->object->{$this->propertyName} = $value = (bool) $value;
    }
}
