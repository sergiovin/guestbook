<?php

namespace Application\Command\Guestbook;

use Application\Adapter\ArrayCollection;
use Application\Command\InsertCommand as AbstractInsertCommand;
use Symfony\Component\Validator\Constraints as Assert;

class InsertCommand extends AbstractInsertCommand
{
    /**
     * @Assert\NotNull(message="Name field must be filled")
     * @Assert\Length(max=50, maxMessage="Max length 50")
     */
    protected $name;

    /**
	 * @Assert\NotNull(message="Email field must be filled")
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email.",
     *     checkMX = false
     * )
     */
    protected $email;

    protected $text;

    public function __construct($name, $email, $text)
    {
        $this->name = $name;
        $this->email = $email;
        $this->text  = $text;
    }

    public function getData()
    {
        return new ArrayCollection(
            array(
                'name' => $this->name,
                'email' => $this->email,
                'text'  => $this->text
            )
        );
    }
}
