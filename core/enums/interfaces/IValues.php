<?php

namespace app\core\enums\interfaces;

/**
 * Implements a method to retrieve values from an enum.
 */
interface IValues
{
    /**
     * Returns an array with all case values.
     * 
     * @return string[] Case values.
     */
    public static function values(): array;
}
