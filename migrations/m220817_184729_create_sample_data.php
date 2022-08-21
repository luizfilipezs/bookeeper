<?php

use app\entities\{
    Author,
    PublishingCompany,
    Tag,
    User
};
use app\forms\BookForm;
use yii\db\Migration;

/**
 * Class m220817_184729_create_sample_data
 */
class m220817_184729_create_sample_data extends Migration
{
        /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Admin user

        $user = new User();
        $user->username = 'admin';
        $user->password = '071de089fe';
        $user->saveOrFail();

        // Author

        $author = new Author();
        $author->name = 'Joseph Conrad';
        $author->nationality = 'Polônia';
        $author->saveOrFail();

        // Book

        $publishingCompany = PublishingCompany::findByName('Antofágica');

        $book = new BookForm();
        $book->title = 'Coração das trevas';
        $book->publishingCompanyId = $publishingCompany->id;
        $book->year = '2020';
        $book->pages = 223;
        $book->saveOrFail();

        // Work

        $tag = Tag::findByName('Romance');

        /** @var \app\entities\Work */
        $work = $book->getWorks()->one();
        $work->addAuthor($author);
        $work->addTag($tag);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "\nm220731_124308_generate_sample_data cannot be reverted.\n";
    }
}
