<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "BookListItem".
 * 
 * @property int $id
 * @property int $bookListId
 * @property ?int $bookId
 * 
 * @property-read Book $book
 */
class BookListItem extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            // common rules
            [['bookListId', 'bookId'], 'required'],
            // relations
            ['bookListId', 'exist', 'skipOnError' => true, 'targetClass' => BookList::class, 'targetAttribute' => 'id'],
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
            'bookListId' => 'Lista de leitura',
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
