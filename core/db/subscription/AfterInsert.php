<?php

namespace app\core\db\subscription;

use Attribute;

/**
 * Subscriber method attribute that marks the method to be called on event
 * `app\core\db\ActiveRecord::EVENT_AFTER_INSERT`.
 * 
 * Usage:
 * 
 * ```php
 * class UserSubscriber
 * {
 *     #[AfterInsert]
 *     public function sendWelcomeMessage(User $user): void
 *     {
 *         // ...
 *     }
 * }
 * ```
 */
#[Attribute(Attribute::TARGET_METHOD)]
class AfterInsert {
}
