<?php

namespace app\core\services;

use app\core\base\CacheInterface;
use app\entities\{
    User,
    UserConfig
};
use Yii;
use yii\base\BaseObject;

/**
 * Provides methods to access data from the logged in user.
 * 
 * @property-read User|null $user Logged in user.
 * @property-read UserConfig|null $userConfig Logged in user configuration.
 */
class UserService extends BaseObject
{
    /**
     * Constructor.
     * 
     * @param CacheInterface $cache Cache mecanism.
     */
    public function __construct(private CacheInterface $cache)
    {
    }

    /**
     * Returns the logged in user.
     * 
     * @return User|null `User` or `null`, if none is logged in.
     */
    public function getUser(): ?User
    {
        return $this->cache->get('user', fn () => Yii::$app->user->identity);
    }

    /**
     * Returns the configuration for the logged in user.
     * 
     * @return UserConfig|null `UserConfig` or `null`, if there is no user logged in.
     */
    public function getUserConfig(): ?UserConfig
    {
        return $this->cache->get('userConfig', fn () => $this->user?->config);
    }
}
