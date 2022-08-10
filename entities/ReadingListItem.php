<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "ReadingListItem".
 * 
 * @property int $id
 * @property int $readingListId
 * @property ?int $bookId
 * @property ?int $workId
 * 
 * @property-read Book|null $book
 * @property-read Work|null $work
 */
class ReadingListItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'readingListId'], 'required'],
            [['bookId'], 'required', 'when' => fn () => !$this->workId],
            [['workId'], 'required', 'when' => fn () => !$this->bookId],
            [['readingListId'], 'exist', 'skipOnError' => true, 'targetClass' => ReadingList::class, 'targetAttribute' => 'id'],
            [['workId'], 'exist', 'skipOnError' => true, 'targetClass' => Work::class, 'targetAttribute' => 'id'],
            [['bookId'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => 'id'],
        ];
    }

    public function getBook(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['id' => 'bookId']);
    }

    public function getWork(): ActiveQuery
    {
        return $this->hasMany(Work::class, ['id' => 'workId']);
    }
}
