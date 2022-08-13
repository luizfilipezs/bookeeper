<?php

namespace app\core\debug;

use Yii;

/**
 * Implements utilities to create logs easier.
 */
final class ErrorLogger
{
    /**
     * Creates an error log for each given value.
     * 
     * @param mixed[] $data Data to be logged.
     */
    public static function log(...$data): void
    {
        foreach ($data as $value) {
            Yii::error($value);
        }
    }

    /**
     * Creates a log for the given exception.
     * 
     * @param \Exception $e Instance of any exception class.
     */
    public static function logException(\Exception $e): void
    {
        self::log([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}
