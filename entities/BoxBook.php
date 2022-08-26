<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "BoxBook".
 * 
 * @property int $id
 * @property int $boxId
 * @property int $bookId
 * @property string $createdAt
 * @property string $updatedAt
 * 
 * @property-read Book $book
 * @property-read Box $box
 */
class BoxBook extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['boxId', 'bookId'], 'required'],
            ['bookId', 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => 'id'],
            ['boxId', 'exist', 'skipOnError' => true, 'targetClass' => Box::class, 'targetAttribute' => 'id'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'boxId' => 'Box',
            'bookId' => 'Livro',
        ];
    }


    /**
     * Returns a query to the related record from table `Box`.
     * 
     * @return ActiveQuery
     */
    public function getBox(): ActiveQuery
    {
        return $this->hasOne(Box::class, ['id' => 'boxId']);
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
