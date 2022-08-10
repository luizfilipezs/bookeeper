<?php

namespace app\subscribers;

use app\core\db\subscription\{
    AfterInsert,
    Subscriber
};
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
    #[AfterInsert]
    public function createUserDefaultReadingList(User $user): void
    {
        $readingList = new ReadingList();
        $readingList->name = 'Livros lidos em ' . date('Y');
        $readingList->userId = $user->id;
        $readingList->save();
    }
}
