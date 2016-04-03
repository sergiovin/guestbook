<?php

namespace Application\Contract;

use Application\ValueObject\Criteria;
use Application\ValueObject\Order;

interface RepositoryManager
{
    public function get($entity);

    public function findOneById($entity, $id);

    public function findOneBy($entity, Criteria $criteria, Order $order = null, $limit = null, $offset = null);

    public function persist($entity);

    public function remove($entity);

    public function flush();

}
