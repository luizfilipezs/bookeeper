<?php

namespace app\core\base;

/**
 * Provides a cache implementation.
 */
class Cache implements CacheInterface
{
    /**
     * Cache data.
     */
    private array $data = [];

    /**
     * {@inheritdoc}
     */
    public function clear(): void
    {
        $this->data = [];
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $key, callable $default = null): mixed
    {
        if (!isset($this->data[$key])) {
            $this->set($key, $default ? call_user_func($default) : null);
        }

        return $this->data[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function set(string $key, mixed $value): void
    {
        $this->data[$key] = $value;
    }
}
