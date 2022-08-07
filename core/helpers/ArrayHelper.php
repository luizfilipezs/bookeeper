<?php

namespace app\core\helpers;

/**
 * Implements utilities for handling arrays.
 */
class ArrayHelper
{
    /**
     * Runs a callback function for every item and return a boolean indicating
     * wether every callback returned `true`.
     * 
     * @param array $array Array to be validated.
     * @param callable $callback Callback validator. The item value and its key will be
     * passed as arguments to it.
     * 
     * @return bool Wether all callbacks returned `true`.
     */
    public static function every(array $array, callable $callback): bool
    {
        foreach ($array as $key => $value) {
            if (!call_user_func($callback, $value, $key)) {
                return false;
            }
        }

        return true;
    }
}
