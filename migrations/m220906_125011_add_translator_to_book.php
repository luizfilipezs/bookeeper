<?php

use app\entities\Book;
use yii\db\Migration;

/**
 * Class m220906_125011_add_translator_to_book
 */
class m220906_125011_add_translator_to_book extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(Book::tableName(), 'translator', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(Book::tableName(), 'translator');
    }
}
