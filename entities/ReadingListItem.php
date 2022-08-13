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
 * 
 * @property-read Book $book
 */
class ReadingListItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            // common rules
            [['readingListId', 'bookId'], 'required'],
            // relations
            ['readingListId', 'exist', 'skipOnError' => true, 'targetClass' => ReadingList::class, 'targetAttribute' => 'id'],
            ['bookId', 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'readingListId' => 'Lista de leitura',
            'bookId' => 'Livro',
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
}
