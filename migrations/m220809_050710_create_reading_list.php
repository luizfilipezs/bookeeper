<?php

use app\entities\{
    Book,
    BookList,
    BookListItem,
    User,
};
use yii\db\Migration;

/**
 * Class m220809_050710_create_reading_list
 */
class m220809_050710_create_reading_list extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(BookList::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        $this->createTable(BookListItem::tableName(), [
            'id' => $this->primaryKey(),
            'bookListId' => $this->integer()->notNull(),
            'bookId' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_reading_list_item_reading_list', BookListItem::tableName(), 'bookListId', BookList::tableName(), 'id');
        $this->addForeignKey('fk_reading_list_item_book', BookListItem::tableName(), 'bookId', Book::tableName(), 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_reading_list_item_book', BookListItem::tableName());
        $this->dropForeignKey('fk_reading_list_item_reading_list', BookListItem::tableName());

        $this->dropTable(BookListItem::tableName());
        $this->dropTable(BookList::tableName());
    }
}
