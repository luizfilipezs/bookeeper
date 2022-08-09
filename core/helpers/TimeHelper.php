<?php

namespace app\core\helpers;

/**
 * Implements utilities for handling time.
 */
final class TimeHelper
{
    /**
     * Returns the quantity of days turned into millisseconds.
     * 
     * @param int $days Quantity of days.
     * 
     * @return int The given quantity of days in milisseconds.
     */
    public static function getDaysInMs(int $days): int
    {
        return 3600 * 24 * $days;
    }
}
