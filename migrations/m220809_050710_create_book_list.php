<?php

use app\entities\{
    Book,
    BookList,
    BookListItem,
    User,
};
use yii\db\Migration;

/**
 * Class m220809_050710_create_book_list
 */
class m220809_050710_create_book_list extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(BookList::tableName(), [
            'id' => $this->primaryKey(),
            'userId' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
        ]);

        $this->addForeignKey('fk_book_list_user', BookList::tableName(), 'userId', User::tableName(), 'id');

        $this->createTable(BookListItem::tableName(), [
            'id' => $this->primaryKey(),
            'bookListId' => $this->integer()->notNull(),
            'bookId' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_book_list_item_book_list', BookListItem::tableName(), 'bookListId', BookList::tableName(), 'id');
        $this->addForeignKey('fk_book_list_item_book', BookListItem::tableName(), 'bookId', Book::tableName(), 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_book_list_item_book', BookListItem::tableName());
        $this->dropForeignKey('fk_book_list_item_book_list', BookListItem::tableName());
        $this->dropTable(BookListItem::tableName());
        $this->dropForeignKey('fk_book_list_user', User::tableName());
        $this->dropTable(BookList::tableName());
    }
}
