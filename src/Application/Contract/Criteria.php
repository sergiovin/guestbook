<?php

namespace Application\Contract;

interface Criteria
{
    public function getCondition();

    public function getParameters();
}
