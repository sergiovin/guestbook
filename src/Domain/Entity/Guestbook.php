<?php

namespace Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * @JMS\ExclusionPolicy("all")
 * @ORM\Entity
 * @ORM\Table(name="x_guestbook")
 */
class Guestbook
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
     * @ORM\Column(type="string", length=50)
	 * @JMS\Expose
     * @JMS\Groups({"web.list"})
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=50)
	 * @JMS\Expose
     * @JMS\Groups({"web.list"})
     */
    protected $email;
	
	
	/**
     * @ORM\Column(type="string", length=50)
	 * @JMS\Expose
     * @JMS\Groups({"web.list"})
     */
    protected $text;

  
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


    public function __construct($name, $email, $text)
    {
        $this->name = $name;
        $this->email = $email;
        $this->text  = $text;
    }

    

    public function update($name, $email, $text)
    {
        $this->name = $name;
        $this->email = $email;
        $this->text  = $text;
    }


	public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getText()
    {
        return $this->text;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getModified()
    {
        return $this->modified;
    }

    public function getData()
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'text' => $this->getText(),
            'created' => $this->getCreated(),
            'modified' => $this->getModified()
        );
    }

   
    
}
