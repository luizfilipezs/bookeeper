<?php

use app\entities\{
    Author,
    PublishingCompany,
    User
};
use app\forms\BookForm;
use yii\db\Migration;

/**
 * Class m220810_022258_generate_sample_data
 */
class m220810_022258_generate_sample_data extends Migration
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
        $book->title = 'Coração das trevas';
        $book->publishingCompanyId = $publishingCompany->id;
        $book->year = '2020';
        $book->pages = 223;
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
