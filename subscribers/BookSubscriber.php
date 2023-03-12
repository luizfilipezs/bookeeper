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
     * Removes all relations from the book being deleted.
     * 
     * @param Book $book The book being deleted.
     */
    #[BeforeDelete]
    public function deleteRelations(Book $book): void
    {
        $book->removeAllWorks();
        $book->removeAllTranslators();
    }
}
