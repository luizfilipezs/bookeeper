<?php

use app\entities\{
    Book,
    BookReading,
    BookReadingWork,
    User,
    Work
};
use yii\db\Migration;

/**
 * Class m220828_161121_create_book_reading
 */
class m220828_161121_create_book_reading extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(BookReading::tableName(), [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'bookId' => $this->integer()->notNull(),
            'isComplete' => $this->boolean()->notNull()->defaultValue(false),
            'startDate' => $this->date(),
            'endDate' => $this->date(),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey('fk_book_reading_user', BookReading::tableName(), 'userId', User::tableName(), 'id');
        $this->addForeignKey('fk_book_reading_book', BookReading::tableName(), 'userId', Book::tableName(), 'id');

        $this->createTable(BookReadingWork::tableName(), [
            'id' => $this->primaryKey(),
            'bookReadingId' => $this->integer()->notNull(),
            'workId' => $this->integer()->notNull(),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ]);

        $this->addForeignKey('fk_book_reading_work_book_reading', BookReadingWork::tableName(), 'bookReadingId', BookReading::tableName(), 'id');
        $this->addForeignKey('fk_book_reading_work_work', BookReadingWork::tableName(), 'workId', Work::tableName(), 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_book_reading_work_work', BookReadingWork::tableName());
        $this->dropForeignKey('fk_book_reading_work_book_reading', BookReadingWork::tableName());

        $this->dropTable(BookReadingWork::tableName());

        $this->dropForeignKey('fk_book_reading_book', BookReading::tableName());
        $this->dropForeignKey('fk_book_reading_user', BookReading::tableName());

        $this->dropTable(BookReading::tableName());
    }
}
