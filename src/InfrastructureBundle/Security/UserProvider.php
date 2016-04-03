<?php

namespace InfrastructureBundle\Security;

use Application\Exception\UnauthorizedException;
use InfrastructureBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {               
        $this->repository = $repository;
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function loadUserByUsername($username)
    {         
        $user = $this->repository->findOneByUsername($username);

        if (!$user) {
            throw new UnauthorizedException('FormBased', 'Unauthorized Exception');
        }
        return $this->loadUser($user);
    }

    protected function loadUser(\Domain\Entity\User $user)
    {
        return new User(
            $user->getId(),
            $user->getUsername(),
            $user->getPassword(),
			$user->getEmail()
        );
    }

    public function supportsClass($class)
    {                                   
        return $class === 'InfrastructureBundle\Security\User';
    }
}
