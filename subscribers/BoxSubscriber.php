<?php

namespace app\subscribers;

use app\core\db\subscription\{
    BeforeDelete,
    Subscriber
};
use app\entities\Box;

/**
 * Handles database related events for entity `Box`.
 */
#[Subscriber]
class BoxSubscriber
{
    /**
     * Removes all books from the box being deleted.
     * 
     * @param Box $box The box being deleted.
     */
    #[BeforeDelete]
    public function removeAllBooksFromBox(Box $box): void
    {
        $box->removeAllBooks();
    }
}
