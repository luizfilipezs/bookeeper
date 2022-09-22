<?php

use app\entities\{
    User,
    UserConfig
};
use yii\db\Migration;

/**
 * Class m220922_021806_create_user_config
 */
class m220922_021806_create_user_config extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(UserConfig::tableName(), [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'reloadFormAfterSaving' => $this->boolean()->defaultValue(false),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey('fk_user_config_user', UserConfig::tableName(), 'userId', User::tableName(), 'id');
        $this->createConfigurationForRegisteredUsers();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_user_config_user', UserConfig::tableName());
        $this->dropTable(UserConfig::tableName());
    }

    /**
     * Creates a configuration record for each registered user.
     */
    private function createConfigurationForRegisteredUsers(): void
    {
        $userIds = User::find()
            ->select('id')
            ->column();

        foreach ($userIds as $userId) {
            $this->createUserConfig($userId);
        }
    }

    /**
     * Creates a configuration record for an user.
     * 
     * @param int $userId The user ID.
     */
    private function createUserConfig(int $userId): void
    {
        $config = new UserConfig();
        $config->userId = $userId;
        $config->saveOrFail();
    }
}
