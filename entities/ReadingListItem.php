<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "ReadingListItem".
 * 
 * @property int $id
 * @property int $readingListId
 * @property ?int $bookId
 * @property ?int $workId
 * 
 * @property-read ReadingListItem[] $items
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

    public function getItems(): ActiveQuery
    {
        return $this->hasMany(ReadingListItem::class, ['readingListId' => 'id']);
    }
}
