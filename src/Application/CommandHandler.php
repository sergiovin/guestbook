<?php

namespace Application;

use Application\Contract\Command as CommandInterface;
use Application\Contract\EventDispatcher;
use Application\Contract\UseCase;
use Application\Contract\User;
use Application\Contract\Validator;
use Application\Event\Events;
use Application\Event\PostCommandEvent;
use Application\Event\PreCommandEvent;
use Application\Exception\BadRequestException;
use Application\Result\Result;

class CommandHandler
{
    private $validator;

    private $eventDispatcher;

    private $user;

    /**
     * @var UseCase[]
     */
    private $useCases;

    public function __construct(
        Validator $validator,
        EventDispatcher $eventDispatcher,
        User $user
    ) {
        $this->validator = $validator;
        $this->eventDispatcher = $eventDispatcher;
        $this->user = $user;
    }

    public function registerCommands(array $useCases)
    {
        foreach ($useCases as $useCase) {
            if ($useCase instanceof UseCase) {
                $this->useCases[$useCase->getManagedCommand()] = $useCase;
            } else {
                throw new \LogicException('CommandHandler::registerCommands UseCase must be array');
            }
        }
    }

    public function execute(CommandInterface $command)
    {
        $this->exceptionIfCommandNotManaged($command);
     
        $this->eventDispatcher->notify(Events::PRE_COMMAND, new PreCommandEvent($command));

        if ($command->isValidated()) {

            $errors = $this->validator->validate($command, $command->getValidationGroups());

            if (!$errors->isEmpty()) {
                throw new BadRequestException('Data validation errors', $errors);
            }
        }

        $result = $this->useCases[get_class($command)]->run($command, $this->user);

        $this->eventDispatcher->notify(Events::POST_COMMAND, new PostCommandEvent($command, $result));

        return new Result($result);
    }

    private function exceptionIfCommandNotManaged(CommandInterface $command)
    {
        $commandClass = get_class($command);

        if (!array_key_exists($commandClass, $this->useCases)) {
            throw new \LogicException($commandClass . ' is not registered command');
        }
    }
}
