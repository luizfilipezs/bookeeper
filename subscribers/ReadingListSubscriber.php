<?php

namespace app\subscribers;

use app\core\db\subscription\{
    BeforeDelete,
    Subscriber
};
use app\entities\ReadingList;

/**
 * Handles database related events for entity `ReadingList`.
 */
#[Subscriber]
class ReadingListSubscriber
{
    /**
     * Removes all items from the list being deleted.
     * 
     * @param ReadingList $readingList The list being deleted.
     */
    #[BeforeDelete]
    public function removeAllItemsFromReadingList(ReadingList $readingList): void
    {
        $readingList->removeAllItems();
    }
}
