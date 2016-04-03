<?php

namespace Application\UseCase\Guestbook;

use Application\Contract\Command as CommandInterface;
use Application\Contract\User as UserInterface;
use Application\UseCase\InsertUseCase as AbstractInsertUseCase;
use Domain\Entity\Guestbook;

class InsertUseCase extends AbstractInsertUseCase
{
    public function run(CommandInterface $_command, UserInterface $_user)
    {
        $this->exceptionIfCommandNotManaged($_command, $_user);

        $data = $_command->getData();

        $guestbook = new Guestbook(
            $data->get('name'),
            $data->get('email'),
            $data->get('text')
        );

        $this->rm->persist($guestbook);
        $this->rm->flush();

        return 'New post was added';
    }
}
