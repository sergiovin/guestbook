<?php

namespace Application\ValueObject;

use Symfony\Component\Validator\Constraints as Assert;

class Order
{
    protected $orders = array();

    public function __construct($params = array())
    {
        foreach ($params as $param) {
            $this->add($param['field'], $param['direction']);
        }
    }

    public function add($field, $direction)
    {
        $this->orders[] = array('field' => $field, 'direction' => $direction);
    }

    public function getContent()
    {
        $result = array();

        foreach ($this->orders as $order) {
            $result[] = $order['field'] . ' ' . $order['direction'];
        }

        return implode(', ', $result) . ' ';
    }
}
