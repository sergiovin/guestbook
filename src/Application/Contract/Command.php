<?php

namespace Application\Contract;

interface Command
{

    public function isValidated();

    /**
     * @return \Domain\Adapter\ArrayCollection
     */
    public function getData();

    /**
     * @return array
     */
    public function getValidationGroups();
}
