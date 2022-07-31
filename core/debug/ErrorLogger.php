<?php

namespace app\core\debug;

use Yii;

class ErrorLogger
{
    public static function log($data)
    {
        Yii::error($data);
    }

    public static function logException(\Exception $e)
    {
        self::log([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}
