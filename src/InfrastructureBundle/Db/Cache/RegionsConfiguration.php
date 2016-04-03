<?php

namespace InfrastructureBundle\Db\Cache;

use Doctrine\ORM\Cache\RegionsConfiguration as SymfonyRegionsConfiguration;

class RegionsConfiguration extends SymfonyRegionsConfiguration
{
    public function __construct($defaultLifetime = 7200, $defaultLockLifetime = 60)
    {
        parent::__construct($defaultLifetime, $defaultLockLifetime);
    }
}
