<?php

namespace InfrastructureBundle\Adapter;

use Application\Contract\Validator as ApplicationValidatorInterface;
use Domain\Adapter\ArrayCollection;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator implements ApplicationValidatorInterface
{
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $value
     * @return \Domain\Adapter\ArrayCollection
     */
    public function validate($value, $groups = null)
    {
        $applicationErrors = new ArrayCollection();
        $errors = $this->validator->validate($value, null, $groups);

        /**
        * @var $error \Symfony\Component\Validator\ConstraintViolation
        */
        foreach ($errors as $error) {
            $applicationErrors->set($error->getPropertyPath(), $error->getMessage());
        }

        return $applicationErrors;
    }
}
