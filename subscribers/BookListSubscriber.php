<?php

namespace app\subscribers;

use app\core\db\subscription\{
    BeforeDelete,
    Subscriber
};
use app\entities\BookList;

/**
 * Handles database related events for entity `BookList`.
 */
#[Subscriber]
class BookListSubscriber
{
    /**
     * Removes all items from the list being deleted.
     * 
     * @param BookList $bookList The list being deleted.
     */
    #[BeforeDelete]
    public function removeAllItemsFromBookList(BookList $bookList): void
    {
        $bookList->removeAllItems();
    }
}
