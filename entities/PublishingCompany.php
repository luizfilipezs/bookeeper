<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "PublishingCompany".
 * 
 * @property int $id
 * @property string $name
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
    public function rules(): array
    {
        return [
            ['name', 'required'],
            ['name', 'string'],
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
