<?php

namespace InfrastructureBundle\Adapter;

use Application\Contract\User as ApplicationUserInterface;
use Domain\Exception\DomainException;
use InfrastructureBundle\Security\User as InfrastructureUser;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

class User implements ApplicationUserInterface
{
    private $storage;

    public function __construct(TokenStorage $storage)
    {
        $this->storage = $storage;
    }

    public function isAllowed($entity, $action)
    {
        return true;
    }

    /**
      * @return InfrastructureBundle\Security\User
      */
    private function getUser()
    {
        if ($token = $this->storage->getToken()) {
			if ($token instanceof AnonymousToken) {
				return new InfrastructureUser(0,'anon.','','');
			}
			else {
                return $token->getUser();
			}
        }

        throw new DomainException('Session error');
    }

    /**
      * @return bool
      */
    public function getReadConditions($entity)
    {
        return true;
    }

    /**
      * @return bool
      */
    public function getWriteConditions($entity)
    {
        return true;
    }

    public function getId()
    {
        return $this->getUser()->getId();
    }

    public function getName()
    {
        return $this->getUser()->getName();
    }

    public function getLocale()
    {
        return 'ru';
    }
   
    public function hasRole($role)
    {
        return $this->getUser()->hasRole($role);
    }
}
