<?php

namespace app\core\bootstrap;

use app\core\db\subscription\{
    ISubscriberLoader,
    SubscriberLoader
};
use Yii;
use yii\base\BootstrapInterface;

class DependencyInjectionBootstrap implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap($app): void
    {
        Yii::$container->set(ISubscriberLoader::class, SubscriberLoader::class);
    }
}
