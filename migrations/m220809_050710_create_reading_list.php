<?php

use app\entities\{
    Book,
    ReadingList,
    ReadingListItem,
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
        $this->createTable(ReadingList::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ]);

        $this->createTable(ReadingListItem::tableName(), [
            'id' => $this->primaryKey(),
            'readingListId' => $this->integer()->notNull(),
            'bookId' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_reading_list_item_reading_list', ReadingListItem::tableName(), 'readingListId', ReadingList::tableName(), 'id');
        $this->addForeignKey('fk_reading_list_item_book', ReadingListItem::tableName(), 'bookId', Book::tableName(), 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_reading_list_item_book', ReadingListItem::tableName());
        $this->dropForeignKey('fk_reading_list_item_reading_list', ReadingListItem::tableName());
        $this->dropForeignKey('fk_reading_list_user', ReadingList::tableName());

        $this->dropTable(ReadingListItem::tableName());
        $this->dropTable(ReadingList::tableName());
    }
}
