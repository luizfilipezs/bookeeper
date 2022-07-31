<?php

use app\entities\PublishingCompany;
use yii\db\Migration;

/**
 * Class m220731_121014_create_publishing_company
 */
class m220731_121014_create_publishing_company extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(PublishingCompany::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(PublishingCompany::tableName());
    }
}
