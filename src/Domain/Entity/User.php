<?php

namespace Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Tests\Models\Cache\Client;
use Domain\Adapter\ArrayCollection;
use Domain\Contract\ManageFiles;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 * @ORM\Entity
 * @ORM\Table(name="x_users")
 */
class User
{
     

    /**
     * @JMS\Expose
     * @JMS\Groups({"web.list"})
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @JMS\Expose
     * @JMS\Groups({"web.list"})
     * @ORM\Column(type="string", length=20, unique=true)
     */
    protected $username;

    /**
     * @ORM\Column(type="string", length=10)
     */
    protected $password;

    
    /**
     * @JMS\Expose
     * @JMS\Groups({"web.list"})
     * @ORM\Column(type="string", length=50, unique=true)
     */
    protected $email;


    /**
     * @Gedmo\Timestampable(on="create")
     * @JMS\Expose
     * @JMS\Groups({"web.list"})
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @JMS\Expose
     * @JMS\Groups({"web.list"})
     * @ORM\Column(type="datetime")
     */
    protected $modified;

    public function __construct(
        $username,
        $password,
        $email
    ) {
        $this->username     = $username;
        $this->password     = $password;
        $this->email        = $email;
    }

    

    public static function generatePassword()
    {
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjklmnpqrstuvwxyz0123456789';

        mt_srand((double)microtime() * 1000000);

        $password = '';

        for ($i = 0; $i < self::PASSWORD_LENGTH; $i++) {
            $password .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        return $password;
    }

    public function update($email) {
        $this->email        = $email;
    }


	public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function getData()
    {
        return array(
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'created' => $this->getCreated(),
            'modified' => $this->getModified()
        );
    }

  
    public function getEmail()
    {
        return $this->email;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getModified()
    {
        return $this->modified;
    }

}
