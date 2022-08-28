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
     * Removes all relations with works from the book reading being deleted.
     * 
     * @param BookReading $bookReading The book reading being deleted.
     */
    #[BeforeDelete]
    public function removeAllWorksFromBookReading(BookReading $bookReading): void
    {
        $bookReading->removeAllWorks();
    }
}
