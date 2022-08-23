<?php

namespace app\forms;

use app\core\exceptions\FriendlyException;
use app\entities\{
    ReadingList,
    Book,
    ReadingListItem
};

/**
 * This form is used to update reading lists.
 */
class ReadingListForm extends ReadingList
{
    /**
     * List of book IDs.
     * 
     * @var string[]
     */
    public $bookIds;

    /**
     * Search input for books. Used in the frontend.
     * 
     * @var string
     */
    public $searchInput;

    /**
     * {@inheritdoc}
     */
    public final static function tableName(): string
    {
        return ReadingList::tableName();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            ['searchInput', 'safe'],
            ['bookIds', 'filter', 'filter' => function ($value) {
                return  is_array($value) ? $value : explode(',', $value);
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
            'searchInput' => 'Adicionar itens',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes): void
    {
        parent::afterSave($insert, $changedAttributes);

        if (!$insert) {
            $this->removeAllItems();
        }

        $this->createItems();
    }

    /**
     * Adds all selected books.
     */
    private function createItems(): void
    {
        foreach ($this->bookIds as $bookId) {
            $this->addBookById($bookId);
        }
    }

    /**
     * Adds a book.
     * 
     * @param int $bookId ID of the book to be added.
     */
    private function addBookById(int $bookId): void
    {
        $item = new ReadingListItem();
        $item->readingListId = $this->id;
        $item->bookId = $bookId;

        if (!$item->save()) {
            $this->handleErrorOnSavingItem($item);
        }
    }

    /**
     * Throws a friendly exception specifying an error on adding an item to the list.
     * 
     * @param ReadingListItem $item Item that could not be saves.
     */
    private function handleErrorOnSavingItem(ReadingListItem $item): void
    {
        $message = ($bookTitle = $item->book->title ?? false) ?
            "Não foi possível adicionar o livro \"{$bookTitle}\" à lista." :
            "Não foi possível adicionar um dos livros à lista.";

        throw new FriendlyException($message);
    }
}
