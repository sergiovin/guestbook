<?php

namespace Application\Event;

use Application\Contract\Command;

class PostCommandEvent
{
    private $command;
    private $response;

    public function __construct(Command $command, $response)
    {
        $this->command = $command;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getResponse()
    {
        return $this->response;
    }
}
