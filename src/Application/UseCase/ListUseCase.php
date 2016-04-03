<?php

namespace Application\UseCase;

use Application\Contract\Command as CommandInterface;
use Application\Contract\User as UserInterface;

abstract class ListUseCase extends AbstractUseCase
{
    public function run(CommandInterface $_command, UserInterface $_user)
    {
        $this->exceptionIfCommandNotManaged($_command, $_user);

        $data = $_command->getData();

        return $this->rm->get($this->getEntityName())->getPaginatorResult(
            $data->get('criteria'),
            $data->get('order'),
            $data->get('limit'),
            $data->get('offset')
        );
    }
}
