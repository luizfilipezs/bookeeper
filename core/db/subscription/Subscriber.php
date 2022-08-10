<?php

namespace app\core\db\subscription;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Subscriber
{
    /**
     * Creates a new attribute instance.
     * 
     * @param string $entity Entity class.
     */
    public function __construct(public readonly string $entity)
    {
    }
}
