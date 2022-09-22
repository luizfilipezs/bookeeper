<?php

namespace app\core\base;

/**
 * Implements methods for handling cached data.
 */
interface CacheInterface
{
    /**
     * Clears the cached data.
     */
    public function clear(): void;

    /**
     * Returns a key value.
     * 
     * @param string $key Key name.
     * @param callable $default (Optional) Callback function that returns a default value,
     * setting it if none is defined.
     * 
     * @return mixed Value of the key.
     */
    public function get(string $key, callable $default = null): mixed;

    /**
     * Sets a key value.
     * 
     * @param string $key Key name.
     * @param mixed $value Value to set.
     */
    public function set(string $key, mixed $value): void;
}
