<?php

use app\entities\Work;
use yii\db\Migration;

/**
 * Class m220731_120714_create_work
 */
class m220731_120714_create_work extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Work::tableName(), [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'subtitle' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(Work::tableName());
    }
}
