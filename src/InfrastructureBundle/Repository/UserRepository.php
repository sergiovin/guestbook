<?php

namespace InfrastructureBundle\Repository;

use Domain\Contract\UserRepository as UserRepositoryInterface;
use Domain\Entity\User;
use Domain\Exception\DomainException;

class UserRepository extends AbstractListRepository implements UserRepositoryInterface
{
    public function getEntityClassName()
    {
        return 'Domain\Entity\User';
    }

    public function persist(User $user)
    {
        if (!$this->canWrite($user)) {
            throw new DomainException('Persist denied');
        }

        $this->em->persist($user);
    }

    public function remove(User $user)
    {
        if (!$this->canWrite($user)) {
            throw new DomainException('Remove denied');
        }

        $this->em->remove($user);
    }

    public function findOneByUsername($username)
    {
        return $this->repository->findOneByUsername($username);
    }

    public function findOneByEmail($email)
    {
        return $this->repository->findOneByEmail($email);
    }

    
}
