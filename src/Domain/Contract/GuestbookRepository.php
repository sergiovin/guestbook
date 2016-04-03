<?php

namespace Domain\Contract;

use Domain\Entity\Guestbook;

interface GuestbookRepository extends RepositoryInterface
{
    public function persist(Guestbook $color);

    public function remove(Guestbook $color);
}
