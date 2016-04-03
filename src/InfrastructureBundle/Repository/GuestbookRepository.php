<?php

namespace InfrastructureBundle\Repository;

use Domain\Contract\GuestbookRepository as GuestbookRepositoryInterface;
use Domain\Entity\Guestbook;
use Domain\Exception\DomainException;

class GuestbookRepository extends AbstractListRepository implements GuestbookRepositoryInterface
{
    public function getEntityClassName()
    {
        return 'Domain\Entity\Guestbook';
    }

    public function persist(Guestbook $guestbook)
    {
        if (!$this->canWrite($guestbook)) {
            throw new DomainException('Persists denied');
        }

        $this->em->persist($guestbook);
    }

    public function remove(Guestbook $guestbook)
    {
        if (!$this->canWrite($guestbook)) {
            throw new DomainException('Remove denied');
        }

        $this->em->remove($guestbook);
    }

}
