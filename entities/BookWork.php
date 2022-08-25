<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for pivot table "BookWork", which represents
 * a relation of many works to one book.
 * 
 * @property int $id
 * @property int $bookId
 * @property int $workId
 * @property string $createdAt
 * @property string $updatedAt
 * 
 * @property-read Book $book
 * @property-read Work $work
 */
class BookWork extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bookId', 'workId'], 'required'],
            [['bookId', 'workId'], 'integer'],
            ['bookId', 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => 'id'],
            ['workId', 'exist', 'skipOnError' => true, 'targetClass' => Work::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * Returns a query to the related record from table `Book`.
     * 
     * @return ActiveQuery
     */
    public function getBook(): ActiveQuery
    {
        return $this->hasOne(Book::class, ['id' => 'bookId']);
    }

    /**
     * Returns a query to the related record from table `Work`.
     * 
     * @return ActiveQuery
     */
    public function getWork(): ActiveQuery
    {
        return $this->hasOne(Work::class, ['id' => 'workId']);
    }
}
