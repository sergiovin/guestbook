<?php

namespace Application\Contract;

interface Validator
{
    public function validate($value, $groups = null);
}
