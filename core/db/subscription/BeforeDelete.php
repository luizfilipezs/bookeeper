<?php

namespace app\core\db\subscription;

use Attribute;

/**
 * Subscriber method attribute that marks the method to be called on event
 * `app\core\db\ActiveRecord::EVENT_BEFORE_DELETE`.
 */
#[Attribute(Attribute::TARGET_METHOD)]
class BeforeDelete
{
}
