<?php

namespace Application\Contract;

use InfrastructureBundle\Security\UserPermissions;

interface User
{
    public function getId();
    public function getName();

    /**
    * @return bool
    */
    public function isAllowed($entity, $action);

    public function hasRole($role);
}
