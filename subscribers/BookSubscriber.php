<?php

namespace app\subscribers;

use app\core\db\subscription\{
    BeforeDelete,
    Subscriber
};
use app\entities\Book;

/**
 * Handles database related events for entity `Book`.
 */
#[Subscriber]
class BookSubscriber
{
    /**
     * Removes all relations with works from the book being deleted.
     * 
     * @param Book $book The book being deleted.
     */
    #[BeforeDelete]
    public function removeAllWorksFromBook(Book $book): void
    {
        $book->removeAllWorks();
    }
}
