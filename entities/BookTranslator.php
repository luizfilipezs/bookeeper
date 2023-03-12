<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for pivot table "BookTranslator", which represents
 * a relation of many translators to one book.
 * 
 * @property int $id
 * @property int $bookId
 * @property int $translatorId
 * 
 * @property-read Translator $translator
 * @property-read Book $book
 */
class BookTranslator extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['bookId', 'translatorId'], 'required'],
            [['bookId', 'translatorId'], 'integer'],
            ['bookId', 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => 'id'],
            ['translatorId', 'exist', 'skipOnError' => true, 'targetClass' => Translator::class, 'targetAttribute' => 'id'],
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
     * Returns a query to the related record from table `Translator`.
     * 
     * @return ActiveQuery
     */
    public function getTranslator(): ActiveQuery
    {
        return $this->hasOne(Translator::class, ['id' => 'translatorId']);
    }
}
