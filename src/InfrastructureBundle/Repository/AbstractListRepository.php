<?php

namespace InfrastructureBundle\Repository;

use Application\ValueObject\Criteria;
use Application\ValueObject\Order;

abstract class AbstractListRepository extends AbstractRepository
{
    public function getArrayResult(Criteria $criteria = null, Order $order = null, $limit = null, $offset = null)
    {
        return $this->getQuery($criteria, $order, $limit, $offset)->getArrayResult();
    }

    public function getPaginatorResult(Criteria $criteria = null, Order $order = null, $limit = null, $offset = null)
    {
        $data = $this->getResult($criteria, $order, $limit, $offset);

        return array(
            'data'  => $data,
            'total' => (is_null($limit) || count($data) < $limit) ? count($data) : $this->getCount($criteria)
        );
    }

    public function getResult(Criteria $criteria = null, Order $order = null, $limit = null, $offset = null)
    {
        return $this->getQuery($criteria, $order, $limit, $offset)->getResult();
    }
}
