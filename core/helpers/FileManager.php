<?php

namespace app\core\helpers;

/**
 * Finds classes by their namespace.
 */
final class FileManager
{
    /**
     * Returns project root directory.
     * 
     * @return string Project directory.
     */
    public static function rootDirectory(): string
    {
        return dirname(__FILE__, 3) . '/';
    }
}
