<?php

namespace app\core\enums;

use app\core\enums\interfaces\{
    ILabel,
    IValues
};

enum Nationality: string implements IValues, ILabel
{
    case American = 'american';
    case Brazilian = 'brazilian';
    case English = 'english';
    case French = 'french';
    case German = 'deutsch';
    case Greek = 'greek';
    case Indian = 'indian';
    case Italian = 'italian';
    case Polish = 'polish';
    case Portuguese = 'portuguese';
    case Roman = 'roman';
    case Russian = 'russian';

    /**
     * {@inheritdoc}
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
            self::American->value => 'Estados Unidos',
            self::Brazilian->value => 'Brasil',
            self::English->value => 'Inglaterra',
            self::French->value => 'França',
            self::German->value => 'Alemanha',
            self::Greek->value => 'Grécia',
            self::Indian->value => 'Índia',
            self::Italian->value => 'Itália',
            self::Polish->value => 'Polônia',
            self::Portuguese->value => 'Portugal',
            self::Roman->value => 'Roma',
            self::Russian->value => 'Rússia',
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
