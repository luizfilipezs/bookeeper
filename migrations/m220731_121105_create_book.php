<?php

use app\core\db\EnumColumnBuilder;
use app\core\enums\BookConservationState;
use app\entities\Book;
use app\entities\PublishingCompany;
use yii\db\Migration;

/**
 * Class m220731_121105_create_book
 */
class m220731_121105_create_book extends Migration
{
    use EnumColumnBuilder;

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(Book::tableName(), [
            'id' => $this->primaryKey(),
            'publishingCompanyId' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'subtitle' => $this->string(),
            'language' => $this->string()->notNull(),
            'pages' => $this->string(),
            'year' => $this->string(),
            'conservationState' => $this->enum(BookConservationState::values()),
        ]);

        $this->addForeignKey('fk_book_publishing_company', Book::tableName(), 'publishingCompanyId', PublishingCompany::tableName(), 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_book_publishing_company', Book::tableName());
        $this->dropTable(Book::tableName());
    }
}
