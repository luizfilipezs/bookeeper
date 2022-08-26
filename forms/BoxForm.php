<?php

namespace app\forms;

use app\core\exceptions\RelationAlreadyExistsException;
use app\entities\{
    Book,
    Box
};

/**
 * This form is used to create/update box records.
 */
class BoxForm extends Box
{
    /**
     * List of book IDs.
     * 
     * @var string[]
     */
    public $bookIds;

    /**
     * {@inheritdoc}
     */
    public final static function tableName(): string
    {
        return Box::tableName();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            ['bookIds', 'filter', 'filter' => function ($value) {
                return  is_array($value) ? $value : [];
            }],
            ['bookIds', 'exist', 'targetClass' => Book::class, 'targetAttribute' => 'id', 'allowArray' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return parent::attributeLabels() + [
            'bookIds' => 'Livros',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if ($this->hasNewBooks()) {
            $this->resetBooks();
        }
    }

    /**
     * Removes the old books and saves the new ones.
     */
    private function resetBooks(): void
    {
        $this->removeAllBooks();
        $this->saveBooks();
    }

    /**
     * Checks whether books list changed.
     * 
     * @return bool Validation result.
     */
    private function hasNewBooks(): bool
    {
        $currentBookIds = $this->getBooks()
            ->select('Book.id')
            ->column();
        
        return $this->bookIds != $currentBookIds;
    }

    /**
     * Creates relations between the book and its books.
     */
    private function saveBooks(): void
    {
        foreach ($this->bookIds as $bookId) {
            $this->addBookById($bookId);
        }
    }

    /**
     * Creates a relation between a book and the box.
     * 
     * @param int $bookId The book identifier.
     */
    private function addBookById(int $bookId): void
    {
        $book = Book::findOne($bookId);

        try {
            $this->addBook($book);
        } catch (RelationAlreadyExistsException $e) {
            return;
        }
    }
}
