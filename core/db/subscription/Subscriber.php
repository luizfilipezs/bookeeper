<?php

namespace app\core\db\subscription;

use Attribute;

/**
 * Marks a class as a subscriber for some entity.
 * 
 * A subscriber class handles database events for some model class.
 * 
 * There are two ways to specify the target entity:
 * 
 * a) Implicitly, using `{EntityName}Suscriber` syntax on class name:
 * 
 * ```php
 * #[Subscriber]
 * class UserSubscriber
 * {
 * }
 * ```
 * 
 * b) Or explicity, passing it as an argument to the attribute:
 * 
 * ```php
 * #[Subscriber(User::class)]
 * class AnyClassName
 * {
 * }
 * ```
 */
#[Attribute(Attribute::TARGET_CLASS)]
class Subscriber
{
    /**
     * Creates a new attribute instance.
     * 
     * @param string $entity Entity class.
     */
    public function __construct(public readonly ?string $entity = null)
    {
    }
}
