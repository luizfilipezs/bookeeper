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
     * Removes all relations with from the work being deleted.
     * 
     * @param Work $work The work being deleted.
     */
    #[BeforeDelete]
    public function deleteRelations(Work $work): void
    {
        $work->removeAllAuthors();
        $work->removeAllTags();
    }
}
