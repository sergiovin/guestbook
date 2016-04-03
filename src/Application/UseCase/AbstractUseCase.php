<?php

namespace Application\UseCase;

use Application\Contract\Command as CommandInterface;
use Application\Contract\RepositoryManager;
use Application\Contract\UseCase;
use Application\Contract\User as UserInterface;
use Application\Exception\AccessDeniedException;

abstract class AbstractUseCase implements UseCase
{
    /**
     * @var RepositoryManager
     */
    protected $rm;


    public function __construct(RepositoryManager $rm)
    {
        $this->rm = $rm;
    }

    public function getEntityName()
    {
        $path = explode('\\', get_class($this));
        return $path[2];
    }

    public function run(CommandInterface $_command, UserInterface $_user)
    {
        $this->exceptionIfCommandNotManaged($_command, $_user);

        return $_command->getData();
    }

    protected function exceptionIfCommandNotManaged(CommandInterface $_command, UserInterface $_user)
    {
        $commandClass = get_class($_command);

        if ($commandClass != $this->getManagedCommand()) {
            throw new \LogicException($commandClass . ' ' . 'is not a managed command');
        }

        if ($_user) {
            $chunks = explode('\\', $commandClass);

            $_entity = $chunks[ sizeof($chunks)-2 ];
            $_action = strtolower(str_replace('Command', '', $chunks[sizeof($chunks) - 1]));

            if (!$_user->isAllowed($_entity, $_action)) {
                throw new AccessDeniedException('You dont have permissions for action'.' '.$_entity.'/'.$_action);
            }
        }
    }

    public function getManagedCommand()
    {
        return str_replace('UseCase', 'Command', get_class($this));
    }
}
