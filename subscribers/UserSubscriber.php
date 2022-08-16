<?php

namespace app\subscribers;

use app\core\db\subscription\{
    AfterInsert,
    BeforeDelete,
    Subscriber
};
use app\core\exceptions\FriendlyException;
use app\entities\{
    ReadingList,
    User
};

/**
 * Handles database related events for entity `User`.
 */
#[Subscriber]
class UserSubscriber
{
    /**
     * Creates a default reading list for a new user.
     * 
     * @param User $user The new user.
     */
    #[AfterInsert]
    public function createUserDefaultReadingList(User $user): void
    {
        $readingList = new ReadingList();
        $readingList->name = 'Lidos em ' . date('Y');
        $readingList->userId = $user->id;
        $readingList->save();
    }

    /**
     * Deletes all reading lists from the user being deleted.
     * 
     * @param User $user The user being deleted.
     * 
     * @throws FriendlyException If a reading list could not be deleted.
     */
    #[BeforeDelete]
    public function removeUserReadingLists(User $user): void
    {
        foreach ($user->readingLists as $readingList) {
            if ($readingList->delete() === false) {
                throw new FriendlyException('Não foi possível remover todas as listas de leitura.');
            }
        }
    }
}
