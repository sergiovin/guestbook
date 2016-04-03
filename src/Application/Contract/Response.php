<?php

namespace Application\Contract;

interface Response
{
    /**
     * @return \Domain\Contract\Collection
     */
    public function getData();
}
