<?php

namespace app\forms;

use app\core\exceptions\FriendlyException;
use app\entities\{
    BookList,
    Book,
    BookListItem
};

/**
 * This form is used to update reading lists.
 */
class BookListForm extends BookList
{
    /**
     * List of book IDs.
     * 
     * @var string[]
     */
    public $bookIds = [];

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
        return BookList::tableName();
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind(): void
    {
        parent::afterFind();

        $this->bookIds = $this->getItems()
            ->select('bookId')
            ->column();
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            ...parent::rules(),
            ['bookIds', 'filter', 'filter' => function ($value) {
                return is_array($value) ? $value : (!!$value ? explode(',', $value) : []);
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
            'searchInput' => 'Adicionar livros',
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
        $item = new BookListItem();
        $item->bookListId = $this->id;
        $item->bookId = $bookId;

        if (!$item->save()) {
            $this->handleErrorOnSavingItem($item);
        }
    }

    /**
     * Throws a friendly exception specifying an error on adding an item to the list.
     * 
     * @param BookListItem $item Item that could not be saves.
     */
    private function handleErrorOnSavingItem(BookListItem $item): void
    {
        $message = ($bookTitle = $item->book->title ?? false) ?
            "Não foi possível adicionar o livro \"{$bookTitle}\" à lista." :
            "Não foi possível adicionar um dos livros à lista.";

        throw new FriendlyException($message);
    }
}
