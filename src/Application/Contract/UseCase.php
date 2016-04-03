<?php

namespace Application\Contract;

interface UseCase
{
    /**
     * @return string
     */
    public function getManagedCommand();

    /**
     * @param Command $command
     * @return mixed
     */
    public function run(Command $_command, User $_user);
}
