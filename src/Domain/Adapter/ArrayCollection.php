<?php

namespace Domain\Adapter;

use Doctrine\Common\Collections\ArrayCollection as DoctrineArrayCollection;

class ArrayCollection extends DoctrineArrayCollection implements \JsonSerializable
{
    public function jsonSerialize()
    {
         return $this->toArray();
    }
}
