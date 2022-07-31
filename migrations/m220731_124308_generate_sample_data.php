<?php

use app\entities\Author;
use app\entities\PublishingCompany;
use app\entities\User;
use app\forms\BookForm;
use yii\db\Migration;

/**
 * Class m220731_124308_generate_sample_data
 */
class m220731_124308_generate_sample_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $user = new User();
        $user->username = 'admin';
        $user->password = '071de089fe';
        $user->authKey = 'adminAuthKey';
        $user->saveOrFail();

        $publishingCompany = new PublishingCompany();
        $publishingCompany->name = 'Antofágica';
        $publishingCompany->saveOrFail();

        $book = new BookForm();
        $book->name = 'Coração das trevas';
        $book->publishingCompanyId = $publishingCompany->id;
        $book->saveOrFail();

        $author = new Author();
        $author->name = 'Joseph Conrad';
        $author->nationality = 'Polônia';
        $author->saveOrFail();

        /** @var \app\entities\Work */
        $work = $book->getWorks()->one();
        $work->addAuthor($author);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "\nm220731_124308_generate_sample_data cannot be reverted.\n";
    }
}
