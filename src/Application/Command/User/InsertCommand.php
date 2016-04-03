<?php

namespace Application\Command\User;

use Application\Adapter\ArrayCollection;
use Application\Command\InsertCommand as AbstractInsertCommand;
use Symfony\Component\Validator\Constraints as Assert;

class InsertCommand extends AbstractInsertCommand
{
    /**
     * @Assert\NotNull(message="Invalid Username")
     * @Assert\Length(min=3, max=20)
     */
    protected $username;

    /**
     * @Assert\NotNull(message="Invalid password")
     * @Assert\Length(min=3, max=10)
     */
    protected $password;
 

    /**
     * @Assert\NotNull(message="Invalid Email")
     * @Assert\Length(max=50)
     * @Assert\Email(checkMX=false)
     */
    protected $email;

     

    public function __construct($_username, $_password, $email)
     {
        $this->username        = $_username;
        $this->password        = $_password;
        $this->email           = $email;
    }

    

    public function getData()
    {
        return new ArrayCollection(
            array(
                'username'        => $this->username,
                'password'        => $this->password,
                'email'           => $this->email
            )
        );
    }
}
