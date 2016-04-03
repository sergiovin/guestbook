<?php

namespace Application\Exception;

use Domain\Adapter\ArrayCollection;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BadRequestException extends BadRequestHttpException
{
    private $errors;

    /**
     * Constructor.
     *
     * @param string     $message  The internal exception message
     * @param \Exception $previous The previous exception
     * @param int        $code     The internal exception code
     */
    public function __construct($message = null, ArrayCollection $errors = null, \Exception $previous = null, $code = 0)
    {
        $this->errors = $errors ? $errors : new ArrayCollection();
        parent::__construct($message, $previous, $code);
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
