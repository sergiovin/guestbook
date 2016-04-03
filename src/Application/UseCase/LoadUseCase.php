<?php

namespace Application\UseCase;

use Application\Contract\Command as CommandInterface;
use Application\Contract\User as UserInterface;
use Application\Exception\NotFoundException;

abstract class LoadUseCase extends AbstractUseCase
{
    public function run(CommandInterface $_command, UserInterface $_user)
    {
        $this->exceptionIfCommandNotManaged($_command, $_user);

        $data = $_command->getData();
        $entity = $this->rm->findOneById($this->getEntityName(), $data->get('id'));


        if (!$entity) {
            throw new NotFoundException('Record not exists');
        }

        return $entity->getData();
    }
}
