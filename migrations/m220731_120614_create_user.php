<?php

use app\entities\User;
use yii\db\Migration;

/**
 * Class m220731_120614_create_user
 */
class m220731_120614_create_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(User::tableName(), [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'authKey' => $this->string(),
            'accessToken' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(User::tableName());
    }
}
