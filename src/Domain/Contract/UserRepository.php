<?php

namespace Domain\Contract;

use Domain\Entity\User;

interface UserRepository extends RepositoryInterface
{
    public function persist(User $user);

    public function remove(User $user);
}
