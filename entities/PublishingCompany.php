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
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string'],
        ];
    }

    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['publishingCompanyId' => 'id']);
    }
}
