<?php

namespace Domain\Contract;

use Domain\Entity\Guestbook;

interface GuestbookRepository extends RepositoryInterface
{
    public function persist(Guestbook $book);

    public function remove(Guestbook $book);
}
