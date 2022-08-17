<?php

namespace app\core\helpers;

interface IArray
{
    public function get(string|int $key): mixed;
    public function set(string|int $key, mixed $value): void;
    public function unset(string|int $key): void;
    public function keys(): array;
    public function hasKey(string|int $key): bool;
    public function push(...$values): void;
    public function forEach(callable $callback): static;
    public function map(callable $callback): static;
    public function reduce(callable $callback): string;
    public function every(callable $callback): bool;
    public function find(callable $callback): mixed;
    public function filter(callable $callback): array;
    public function includes($value): bool;
    public function indexOf($value): int;
    public function sum(): int|float;
    public function asArray(): array;
    public function join(string $separator = ''): string;
}
