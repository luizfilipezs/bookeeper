<?php

namespace app\core\helpers;

class Arr implements IArray, \Iterator, \ArrayAccess
{
    /**
     * Actual array items.
     * 
     * @var mixed[]
     */
    private array $values;

    /**
     * Current iterator position.
     * 
     * @var int
     */
    private int $position = 0;

    /**
     * Creates a new instance.
     * 
     * @param mixed[] $values Array items.
     */
    public function __construct(array $values)
    {
        $this->values = $values;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->hasKey($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->get($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->unset($offset);
    }

    /**
     * {@inheritdoc}
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function current(): mixed
    {
        return $this->values[$this->key()];
    }

    /**
     * {@inheritdoc}
     */
    #[\ReturnTypeWillChange]
    public function key(): int|string
    {
        return $this->keys()[$this->position];
    }

    /**
     * {@inheritdoc}
     */
    public function next(): void
    {
        ++$this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return $this->position < count($this->keys());
    }

    /**
     * {@inheritdoc}
     */
    public function keys(): array
    {
        return array_keys($this->values);
    }

    /**
     * {@inheritdoc}
     */
    public function hasKey(string|int $key): bool
    {
        return array_key_exists($key, $this->values);
    }

    /**
     * {@inheritdoc}
     */
    public function get(string|int $key): mixed
    {
        if (!$this->hasKey($key)) {
            throw new \InvalidArgumentException("Key \"{$key}\" does not exist on array.");
        }

        return $this->values[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function set(string|int $key, mixed $value): void
    {
        $this->values[$key] = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function unset(string|int $key): void
    {
        $this->get($key);
        unset($this->values[$key]);
    }

    /**
     * {@inheritdoc}
     */
    public function push(...$values): void
    {
        array_push($this->values, ...$values);
    }

    /**
     * {@inheritdoc}
     */
    public function forEach(callable $callback): static
    {
        foreach ($this->values as $key => $value) {
            call_user_func($callback, $value, $key);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function map(callable $callback): static
    {
        foreach ($this->values as $key => $value) {
            $this->values[$key] = call_user_func($callback, $value, $key);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function reduce(callable $callback): string
    {
        return array_reduce($this->values, $callback, '');
    }

    /**
     * {@inheritdoc}
     */
    public function every(callable $callback): bool
    {
        foreach ($this->values as $key => $value) {
            if (!call_user_func($callback, $value, $key)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function includes($value): bool
    {
        return $this->indexOf($value) !== -1;
    }

    /**
     * {@inheritdoc}
     */
    public function find(callable $callback): mixed
    {
        foreach ($this->values as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                return $value;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function filter(callable $callback): array
    {
        $results = [];

        foreach ($this->values as $key => $value) {
            if (call_user_func($callback, $value, $key)) {
                $results[] = $value;
            }
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function indexOf($value): int
    {
        foreach ($this->values as $key => $arrayValue) {
            if ($value === $arrayValue) {
                return $key;
            }
        }

        return -1;
    }

    /**
     * {@inheritdoc}
     */
    public function sum(): int|float
    {
        return array_sum($this->values);
    }

    /**
     * {@inheritdoc}
     */
    public function asArray(): array
    {
        return $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function join(string $separator = ''): string
    {
        return implode($separator, $this->values);
    }
}
