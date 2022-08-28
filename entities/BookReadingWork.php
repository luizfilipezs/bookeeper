<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for pivot table "BookReadingWork", which represents
 * a relation of many works to one book reading.
 * 
 * @property int $id
 * @property int $bookReadingId
 * @property int $workId
 * @property string $createdAt
 * @property string $updatedAt
 * 
 * @property-read BookReading $bookReading
 * @property-read Work $work
 */
class BookReadingWork extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bookReadingId', 'workId'], 'required'],
            [['bookReadingId', 'workId'], 'integer'],
            ['bookReadingId', 'exist', 'skipOnError' => true, 'targetClass' => BookReading::class, 'targetAttribute' => 'id'],
            ['workId', 'exist', 'skipOnError' => true, 'targetClass' => Work::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * Returns a query to the related record from table `BookReading`.
     * 
     * @return ActiveQuery
     */
    public function getBookReading(): ActiveQuery
    {
        return $this->hasOne(BookReading::class, ['id' => 'bookReadingId']);
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
