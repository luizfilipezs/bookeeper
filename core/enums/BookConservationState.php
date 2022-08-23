<?php

namespace app\core\enums;

use app\core\enums\interfaces\ILabel;

enum BookConservationState: string implements ILabel
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
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    public function label(): string
    {
        $labels = self::labels();

        return $labels[$this->value];
    }
}
