<?php

namespace app\core\enums;

enum BookConservationState: string
{
    case New = 'new';
    case Regular = 'regular';
    case Dusty = 'dusty';
    case MissingPages = 'missingPages';
    case DustyAndMissingPages = 'dusty,missingPages';

    /**
     * Returns an array with all case values.
     * 
     * @return string[] Case values.
     */
    public static function values(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }

    /**
     * Returns an array with all case labels.
     * 
     * @return string[] Case labels.
     */
    public static function labels(): array
    {
        return [
            self::New->value => 'Novo',
            self::Regular->value => 'Normal',
            self::Dusty->value => 'Empoeirado',
            self::MissingPages->value => 'Com páginas faltando',
            self::DustyAndMissingPages->value => 'Empoeirado e com páginas faltando',
        ];
    }

    /**
     * Returns the label for the current value.
     * 
     * @return string Value label.
     */
    public function label(): string
    {
        $labels = self::labels();

        return $labels[$this->value];
    }
}
