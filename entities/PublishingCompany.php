<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the model class for table "PublishingCompany".
 * 
 * @property int $id
 * @property string $name
 * @property string $createdAt
 * @property string $updatedAt
 * 
 * @property-read Book[] $book
 */
class PublishingCompany extends ActiveRecord
{
    /**
     * Finds a record by the column `name`.
     * 
     * @param string $name Publishing company name.
     * 
     * @return static|null Publishing company matching the name, or `null` if nothing matches.
     */
    public static function findByName(string $name): ?static
    {
        return static::findOne(['name' => $name]);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'createdAt',
                'updatedAtAttribute' => 'updatedAt',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string'],
            ['name', 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Nome',
            'createdAt' => 'Criada em',
            'updatedAt' => 'Atualizada em',
        ];
    }

    /**
     * Returns a query to the related records from table `Book`.
     * 
     * @return ActiveQuery
     */
    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['publishingCompanyId' => 'id']);
    }
}
