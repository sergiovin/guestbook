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
            throw new DomainException('Сохранение запрещено');
        }

        $this->em->persist($guestbook);
    }

    public function remove(Guestbook $guestbook)
    {
        if (!$this->canWrite($guestbook)) {
            throw new DomainException($this->translator->trans('Удаление запрещено'));
        }

        $this->em->remove($guestbook);
    }

}
