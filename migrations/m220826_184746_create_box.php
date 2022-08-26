<?php

use app\entities\{
    Book,
    Box,
    BoxBook
};
use yii\db\Migration;

/**
 * Class m220826_184746_create_box
 */
class m220826_184746_create_box extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Box::tableName(), [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ]);

        $this->createTable(BoxBook::tableName(), [
            'id' => $this->primaryKey(),
            'boxId' => $this->integer()->notNull(),
            'bookId' => $this->integer()->notNull(),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ]);

        $this->addforeignKey('fk_box_book_box', BoxBook::tableName(), 'boxId', Box::tableName(), 'id');
        $this->addforeignKey('fk_box_book_book', BoxBook::tableName(), 'bookId', Book::tableName(), 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropforeignKey('fk_box_book_book', BoxBook::tableName());
        $this->dropforeignKey('fk_box_book_box', BoxBook::tableName());

        $this->dropTable(BoxBook::tableName());
        $this->dropTable(Box::tableName());
    }
}
