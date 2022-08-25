<?php

use app\entities\PublishingCompany;
use yii\db\Migration;

/**
 * Class m220731_121014_create_publishing_company
 */
class m220731_121014_create_publishing_company extends Migration
{
    /**
     * Publishing companies to create by default.
     * 
     * @var string[]
     */
    const PUBLISHING_COMPANY_NAMES = [
        'Abril',
        'Aleph',
        'Angelotti Ltda.',
        'Antofágica',
        'Ateliê Editorial',
        'Autêntica',
        'Canterbury Classics',
        'Clube de Literatura Clássica',
        'Companhia das Letras',
        'Cosac Naify',
        'Editora 34',
        'Global',
        'Intrínseca',
        'L&PM',
        'Martin Claret',
        'Mises Brasil',
        'Nova Aguilar',
        'Nova Fronteira',
        'Opera Mundi',
        'Penguin — Companhia',
        'Planeta',
        'Record',
        'Rocco',
        'Senado Federal',
        'Topbooks',
        'Vide Editorial',
        'Zahar',
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable(PublishingCompany::tableName(), [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'createdAt' => $this->dateTime()->notNull(),
            'updatedAt' => $this->dateTime()->notNull(),
        ]);

        $this->createDefaultPublishingCompanies();
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable(PublishingCompany::tableName());
    }

    /**
     * Creates all publishing companies defined in the constant `PUBLISHING_COMPANY_NAMES`.
     */
    private function createDefaultPublishingCompanies(): void
    {
        foreach (self::PUBLISHING_COMPANY_NAMES as $company) {
            $publishingCompany = new PublishingCompany();
            $publishingCompany->name = $company;
            $publishingCompany->saveOrFail();
        }
    }
}
