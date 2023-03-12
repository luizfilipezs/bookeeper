<?php

namespace app\subscribers;

use app\core\db\subscription\{
    BeforeDelete,
    Subscriber
};
use app\entities\BookReading;

/**
 * Handles database related events for entity `BookReading`.
 */
#[Subscriber]
class BookReadingSubscriber
{
    /**
     * Removes all relations with from the book reading being deleted.
     * 
     * @param BookReading $bookReading The book reading being deleted.
     */
    #[BeforeDelete]
    public function deleteRelations(BookReading $bookReading): void
    {
        $bookReading->removeAllWorks();
    }
}
