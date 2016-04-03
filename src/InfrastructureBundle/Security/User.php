<?php

namespace InfrastructureBundle\Security;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements AdvancedUserInterface, EquatableInterface, \Serializable
{
    private $id;
    private $username;
    private $password;
	private $email;

    public function __construct($id, $username, $password, $email)
    {
        $this->id           = $id;
        $this->username     = $username;
        $this->password     = $password;
		$this->email     = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSalt()
    {
        return null;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getName()
    {
        return $this->username;
    }

	public function getEmail()
    {
        return $this->email;
    }
	
    public function eraseCredentials()
    {
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->id !== $user->getId()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }
		
		if ($this->email !== $user->getEmail()) {
            return false;
        }


        return true;
    }

    public function getId()
    {
        return $this->id;
    }


    public function isAllowed($entity, $action)
    {
        return true;
    }


    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }
	
    public function getRoles()
    { 
		return $this->getId()>0 ? array('ROLE_USER') : array('ROLE_ANONYMOUS'); 
    }

	public function hasRole($role)
    {
        return in_array((string)$role, $this->getRoles());
    }

    public function isEnabled()
    {
        return true;
    }
	

    public function serialize()
    {
        return serialize(
            array(
                $this->id,
                $this->username,
                $this->password,
				$this->email,
            )
        );
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->username,
            $this->password,
			$this->email
            ) = unserialize($serialized);
    }

    public function getLocale()
    {
        return 'ru';
    }
}
