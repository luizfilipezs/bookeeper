<?php

namespace app\core\enums\interfaces;

/**
 * Implements methods to retrieve labels from an enum.
 */
interface ILabel
{
    /**
     * Returns an array with all case labels.
     * 
     * @return string[] Case labels.
     */
    public static function labels(): array;

    /**
     * Returns the label for the current value.
     * 
     * @return string Value label.
     */
    public function label(): string;
}
