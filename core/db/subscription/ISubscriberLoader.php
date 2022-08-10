<?php

namespace app\core\db\subscription;

use app\core\exceptions\SubscriberSetupException;

/**
 * Interface that should be implemented by subscriber loader classes.
 */
interface ISubscriberLoader
{
    /**
     * Loads all subscribers.
     * 
     * @throws SubscriberSetupException If any subscriber has errors.
     */
    public function loadAll(): void;

    /**
     * Loads one or more subscribers.
     * 
     * @param string|string[] $subscribers Subscribers to load.
     * 
     * @throws SubscriberSetupException If any subscriber has errors.
     */
    public function load(string|array $subscribers): void;
}
