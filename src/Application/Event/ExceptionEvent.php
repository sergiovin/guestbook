<?php

namespace Application\Event;

use Application\Contract\Command;

class ExceptionEvent
{
    private $command;
    private $exception;

    public function __construct(Command $command, \Exception $exception)
    {
        $this->command = $command;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getException()
    {
        return $this->exception;
    }
}
