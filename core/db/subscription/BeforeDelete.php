<?php

namespace app\core\db\subscription;

use app\core\db\ActiveRecord;
use Attribute;

/**
 * Subscriber attribute that marks a method to be called before the entity gets deleted.
 */
#[Attribute(Attribute::TARGET_METHOD)]
class BeforeDelete
{
    /**
     * Creates a new attribute instance.
     * 
     * @param string $eventName Name of the event that should trigger the methods with this attribute.
     */
    public function __construct(public string $eventName = ActiveRecord::EVENT_BEFORE_DELETE)
    {
    }
}
