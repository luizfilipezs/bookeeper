<?php

use app\entities\{
    Author,
    Work,
    WorkAuthor
};
use yii\db\Migration;

/**
 * Class m220731_120828_create_work_author
 */
class m220731_120828_create_work_author extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(WorkAuthor::tableName(), [
            'id' => $this->primaryKey(),
            'workId' => $this->integer()->notNull(),
            'authorId' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_work_author_work', WorkAuthor::tableName(), 'workId', Work::tableName(), 'id');
        $this->addForeignKey('fk_work_author_author', WorkAuthor::tableName(), 'authorId', Author::tableName(), 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_work_author_author', WorkAuthor::tableName());
        $this->dropForeignKey('fk_work_author_work', WorkAuthor::tableName());

        $this->dropTable(WorkAuthor::tableName());
    }
}
