<?php

namespace app\core\bootstrap;

use app\core\db\subscription\ISubscriberLoader;
use yii\base\BootstrapInterface;

/**
 * Handles subscriber registrations during the bootstrap process.
 */
class SubscriberBootstrap implements BootstrapInterface
{
    /**
     * Initialization.
     * 
     * @param ISubscriberLoader $subscriberLoader Component that registers application subscribers.
     */
    public function __construct(private ISubscriberLoader $subscriberLoader)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app): void
    {
        $this->subscriberLoader->loadAll();
    }
}
