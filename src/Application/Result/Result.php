<?php

namespace Application\Result;

use JMS\Serializer\Annotation as JMS;

class Result
{
    private $_result;

    public function __construct($result = null)
    {
        $this->_result = $result;
    }

    public function get()
    {
        return $this->_result;
    }
}
