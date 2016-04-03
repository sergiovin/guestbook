<?php

namespace Domain\Contract;

use Application\ValueObject\Criteria;
use Application\ValueObject\Order;

interface RepositoryInterface
{
    public function getResult(Criteria $criteria, Order $order = null, $limit = null, $offset = null);

    public function getArrayResult(Criteria $criteria, Order $order = null, $limit = null, $offset = null);

    public function getPaginatorResult(Criteria $criteria, Order $order = null, $limit = null, $offset = null);

    public function findOneBy(Criteria $criteria, Order $order = null, $limit = null, $offset = null);

    public function findOneById($id);

    public function evict();
}
