<?php

namespace app\subscribers;

use app\core\db\subscription\{
    BeforeDelete,
    Subscriber
};
use app\entities\Work;

/**
 * Handles database related events for entity `Work`.
 */
#[Subscriber]
class WorkSubscriber
{
    /**
     * Removes all relations with authors from the work being deleted.
     * 
     * @param Work $work The work being deleted.
     */
    #[BeforeDelete]
    public function removeAllAuthorsFromWork(Work $work): void
    {
        $work->removeAllAuthors();
    }

    /**
     * Removes all relations with tags from the work being deleted.
     * 
     * @param Work $work The work being deleted.
     */
    #[BeforeDelete]
    public function removeAllTagsFromWork(Work $work): void
    {
        $work->removeAllTags();
    }
}
