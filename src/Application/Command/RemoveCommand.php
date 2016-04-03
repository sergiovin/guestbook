<?php

namespace Application\Command;

use Application\Adapter\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

abstract class RemoveCommand extends AbstractCommand
{
    /**
     * @Assert\Count(min="1", minMessage="Необходимо выбрать объявление(я)")
     * @Assert\All({
     *      @Assert\NotNull(message="""id объекта"" обязательно к заполнению"),
     *      @Assert\Regex(pattern="/^\d{1,}$/", message="""id объекта"" должен содержать только цифры")
     * })
     */
    protected $id;

    public function __construct($id)
    {
        $this->id = (array)$id;
    }

    public function getData()
    {
        return new ArrayCollection(
            array(
                'id' => $this->id
            )
        );
    }
}
