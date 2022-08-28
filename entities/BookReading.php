<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the model class for table "BookReading".
 * 
 * @property int $id
 * @property int $userId
 * @property int $bookId
 * @property bool $isComplete
 * @property string $startDate
 * @property string $endDate
 * @property string $createdAt
 * @property string $updatedAt
 * 
 * @property-read Book $book
 * @property-read User $user
 * @property-read Work[] $works
 */
class BookReading extends ActiveRecord
{
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
            [['bookId'], 'required'],
            [['userId', 'bookId'], 'integer'],
            [['isComplete'], 'boolean'],
            [['startDate', 'endDate'], 'date', 'format' => 'php:Y-m-d'],
            ['userId', 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => 'id'],
            ['bookId', 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * Returns a query to the related record from table `User`.
     * 
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
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
     * Returns a query to the related records from table `Work` via a JOIN
     * operation with the pivot table `BookReadingWork`.
     * 
     * @return ActiveQuery
     */
    public function getWorks(): ActiveQuery
    {
        return $this->hasRelation(Work::class);
    }

    /**
     * Creates a new relation between the given `Work` and the current book reading.
     * 
     * @param Work $work Work to be added to the current book reading.
     * 
     * @return BookReadingWork Pivot relation record.
     * 
     * @throws RelationAlreadyExistsException If relation already exists.
     */
    public function addWork(Work $work): BookReadingWork
    {
        return $this->addRelation($work);
    }

    /**
     * Removes an existing relation between the given `Work` and the current book reading.
     * 
     * @param Work $work Work to be removed from the current book reading.
     */
    public function removeWork(Work $work): void
    {
        $this->removeRelation($work);
    }

    /**
     * Removes all relations with works.
     */
    public function removeAllWorks(): void
    {
        foreach ($this->works as $work) {
            $this->removeWork($work);
        }
    }
}
