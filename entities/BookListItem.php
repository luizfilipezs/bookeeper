<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\Expression;

/**
 * This is the model class for table "BookListItem".
 * 
 * @property int $id
 * @property int $bookListId
 * @property int $bookId
 * @property string $createdAt
 * @property string $updatedAt
 * 
 * @property-read Book $book
 */
class BookListItem extends ActiveRecord
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
            'createdAt' => 'Criado em',
            'updatedAt' => 'Atualizado em',
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
