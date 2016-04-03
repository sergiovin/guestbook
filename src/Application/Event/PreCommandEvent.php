<?php

namespace Application\Event;

use Application\Contract\Command;

class PreCommandEvent
{
    private $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function getCommand()
    {
        return $this->command;
    }
}
