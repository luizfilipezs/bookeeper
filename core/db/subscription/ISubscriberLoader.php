<?php

namespace app\core\db\subscription;

/**
 * Interface that should be implemented by subscriber loader classes.
 */
interface ISubscriberLoader
{
    /**
     * Loads all subscribers.
     */
    public function loadAll(): void;

    /**
     * Loads one or more subscribers.
     * 
     * @param string|string[] $subscribers Subscribers to load.
     */
    public function load(string|array $subscribers): void;
}
