<?php

use app\entities\{
    Book,
    BookWork,
    Work
};
use yii\db\Migration;

/**
 * Class m220731_121253_create_book_work
 */
class m220731_121253_create_book_work extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(BookWork::tableName(), [
            'id' => $this->primaryKey(),
            'bookId' => $this->integer()->notNull(),
            'workId' => $this->integer()->notNull(),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey('fk_book_work_book', BookWork::tableName(), 'bookId', Book::tableName(), 'id');
        $this->addForeignKey('fk_book_work_work', BookWork::tableName(), 'workId', Work::tableName(), 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_book_work_work', BookWork::tableName());
        $this->dropForeignKey('fk_book_work_book', BookWork::tableName());

        $this->dropTable(BookWork::tableName());
    }
}
