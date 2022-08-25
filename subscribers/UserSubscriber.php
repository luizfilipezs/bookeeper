<?php

namespace app\subscribers;

use app\core\db\subscription\{
    AfterInsert,
    BeforeDelete,
    Subscriber
};
use app\core\exceptions\FriendlyException;
use app\entities\{
    BookList,
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
    public function createUserDefaultBookList(User $user): void
    {
        $bookList = new BookList();
        $bookList->name = 'Lidos em ' . date('Y');
        $bookList->userId = $user->id;
        $bookList->save();
    }

    /**
     * Deletes all reading lists from the user being deleted.
     * 
     * @param User $user The user being deleted.
     * 
     * @throws FriendlyException If a reading list could not be deleted.
     */
    #[BeforeDelete]
    public function removeUserBookLists(User $user): void
    {
        foreach ($user->bookLists as $bookList) {
            $this->deleteBookList($bookList);
        }
    }

    /**
     * Deletes a reading list.
     * 
     * @param BookList $bookList Record to delete.
     * 
     * @throws FriendlyException If the record could not be deleted.
     */
    private function deleteBookList(BookList $bookList): void
    {
        if ($bookList->delete() === false) {
            throw new FriendlyException('Não foi possível remover todas as listas de leitura.');
        }
    }
}
