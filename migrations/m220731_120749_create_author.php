<?php

use app\entities\Author;
use yii\db\Migration;

/**
 * Class m220731_120749_create_author
 */
class m220731_120749_create_author extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Author::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'nationality' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Author::tableName());
    }
}
