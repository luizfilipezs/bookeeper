<?php

namespace app\core\enums;

use app\core\enums\interfaces\ILabel;

enum Nationality: string implements ILabel
{
    case Brazilian = 'brazilian';
    case English = 'english';
    case French = 'french';
    case Indian = 'indian';
    case Italian = 'italian';
    case Polish = 'polish';
    case Portuguese = 'portuguese';
    case Russian = 'russian';

    /**
     * {@inheritdoc}
     */
    public static function labels(): array
    {
        return [
            self::Brazilian => 'Brasil',
            self::English => 'Inglaterra',
            self::French => 'França',
            self::Indian => 'Índia',
            self::Italian => 'Itália',
            self::Polish => 'Polônia',
            self::Portuguese => 'Portugal',
            self::Russian => 'Rússia',
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
