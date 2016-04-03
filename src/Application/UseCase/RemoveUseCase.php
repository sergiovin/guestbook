<?php

namespace Application\UseCase;

use Application\Contract\Command as CommandInterface;
use Application\Contract\User as UserInterface;
use Application\Exception\NotFoundException;

abstract class RemoveUseCase extends AbstractUseCase
{
    public function run(CommandInterface $_command, UserInterface $_user)
    {
        $this->exceptionIfCommandNotManaged($_command, $_user);

        $data = $_command->getData();

        $entityName = $this->getEntityName();

        foreach ($data->get('id') as $id) {

            $entity = $this->rm->findOneById($entityName, $id);

            if (!$entity) {
                throw new NotFoundException('Record not exists');
            }

            $this->rm->remove($entity);
        }

        $this->rm->flush();

        return 'Selected records are deleted';
    }
}
