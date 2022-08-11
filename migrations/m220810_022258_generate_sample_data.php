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
     * Publishing companies to create.
     * 
     * @var string[]
     */
    const PUBLISHING_COMPANY_NAMES = [
        'Antofágica',
        'Companhia das Letras',
        'Clube de Literatura Clássica',
        'Martin Claret',
        'Aleph',
        'Penguin — Companhia',
        'Nova Fronteira',
        'L&PM',
        'Editora 34',
        'Abril',
        'Canterbury Classics',
        'Nova Aguilar',
        'Topbooks',
        'Record',
        'Global',
        'Angelotti Ltda.',
        'Ateliê Editorial',
        'Planeta',
        'Senado Federal',
        'Vide Editorial',
        'Mises Brasil',
        'Intrínseca',
        'Zahar',
        'Autêntica',
        'Rocco',
        'Opera Mundi',
        'Cosac Naify',
    ];

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

        // Publishing companies

        $this->createPublishingCompanies();

        // Author

        $author = new Author();
        $author->name = 'Joseph Conrad';
        $author->nationality = 'Polônia';
        $author->saveOrFail();

        // Book

        $book = new BookForm();
        $book->title = 'Coração das trevas';
        $book->publishingCompanyId = PublishingCompany::findByName('Antofágica')->id;
        $book->year = '2020';
        $book->pages = 223;
        $book->saveOrFail();

        // Work

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

    /**
     * Creates all publishing companies defined in the constant `PUBLISHING_COMPANY_NAMES`.
     */
    private function createPublishingCompanies(): void
    {
        foreach (self::PUBLISHING_COMPANY_NAMES as $company) {
            $publishingCompany = new PublishingCompany();
            $publishingCompany->name = $company;
            $publishingCompany->saveOrFail();
        }
    }
}
