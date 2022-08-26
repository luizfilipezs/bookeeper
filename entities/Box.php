<?php

namespace app\entities;

use app\core\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "Box".
 * 
 * @property int $id
 * @property string $title
 * @property string $createdAt
 * @property string $updatedAt
 * 
 * @property-read Book[] $books
 */
class Box extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ['title', 'required'],
            ['title', 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Título',
        ];
    }

    /**
     * Returns a query to the related records from table `Book` via a JOIN
     * operation with the pivot table `BoxBook`.
     * 
     * @return ActiveQuery
     */
    public function getBooks(): ActiveQuery
    {
        return $this->hasRelation(Book::class);
    }

    /**
     * Creates a new relation between the given `Book` and the current work.
     * 
     * @param Book $book Book to be added to the current work.
     * 
     * @return BoxBook Pivot relation record.
     * 
     * @throws RelationAlreadyExistsException If relation already exists.
     */
    public function addBook(Book $book): BoxBook
    {
        return $this->addRelation($book);
    }

    /**
     * Removes an existing relation between the given `Book` and the current work.
     * 
     * @param Book $book Book to be removed from the current work.
     */
    public function removeBook(Book $book): void
    {
        $this->removeRelation($book);
    }

    /**
     * Removes all relations with books.
     */
    public function removeAllBooks(): void
    {
        foreach ($this->books as $book) {
            $this->removeBook($book);
        }
    }
}
