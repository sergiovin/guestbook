<?php

namespace InfrastructureBundle\Repository;

use Application\Contract\Translator;
use Application\Contract\User;
use Application\ValueObject\Criteria;
use Application\ValueObject\Order;
use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr;

abstract class AbstractRepository
{
    protected $em;

    protected $user;

    protected $repository;

    public function __construct(EntityManager $em, User $user)
    {
        $this->em = $em;
        $this->user = $user;
        $this->repository = $this->em->getRepository($this->getEntityClassName());
    }

    abstract public function getEntityClassName();

    public function evict($id = null)
    {
        if ($this->isCacheble()) {
            if ($id) {

                $this->em->getCache()->evictEntity($this->getEntityClassName(), $id);
            } else {
                $this->em->getCache()->evictEntityRegion($this->getEntityClassName());
            }
        }
    }

    protected function isCacheble()
    {
        return false;
    }

    public function getCount(Criteria $criteria = null)
    {
        return (int)$this->getCountQuery($criteria)->getSingleScalarResult();
    }

    protected function getCountQuery(Criteria $criteria = null)
    {
        $dql = $this->getCountDQL($criteria);
        $query = $this->em->createQuery($dql);

        if (!is_null($criteria)) {
            $query->setParameters($criteria->getParameters());
        }

        return $query;
    }

    /**
     * @return string
     */
    private function getCountDQL(Criteria $criteria = null)
    {
        return preg_replace(
            '/SELECT (.*) FROM/',
            'SELECT COUNT(DISTINCT ' . $this->getEntityName() . ') FROM',
            $this->getDQL($criteria)
        );
    }

    public function getEntityName($entityClassName = null)
    {
        if (!$entityClassName) {
            $entityClassName = $this->getEntityClassName();
        }

        $parts = explode('\\', $entityClassName);
        return array_pop($parts);
    }

    private function getDQL(Criteria $criteria = null, Order $order = null)
    {

        $entities = $this->getAssociationEntities();

        $dql = 'SELECT ' . $this->getEntityName();
        $not_cached_entities = array_filter($entities, function ($key) {
            return !$this->isCachebleAssociation($key);
        }, ARRAY_FILTER_USE_KEY);


        if (count($not_cached_entities)) {
            $dql .= ', ' . implode(', ', array_values($entities));
        }

        $dql .= ' FROM ' . $this->getEntityClassName() . ' ' . $this->getEntityName();

        foreach ($entities as $field => $entity) {
            $dql  .= ' LEFT JOIN ' . $this->getEntityName() . '.' . $field . ' ' . $entity;
        }

        if (!is_null($criteria)) {
            $dql .= $criteria->getCondition(' WHERE ');
        }

        if (!is_null($order)) {
            $dql .= ' ORDER BY ' . $order->getContent();
        }

        return $dql;
    }

    protected function getAssociationEntities()
    {
        $meta = $this->em->getClassMetadata($this->getEntityClassName());

        $entities = array();
        foreach ($meta->getAssociationMappings() as $name => $row) {
            //игнорируем связи ManyToMany
            if ($row['type'] == 8) {
                continue;
            }

            if (!in_array($name, array('child', 'children', 'parent'))) {
                $entities[$name] = str_replace(' ', '', ucwords(str_replace('_', ' ', $row['fieldName'])));
            } elseif (in_array($name, array('child', 'parent'))) {
                $entities[$name] = $row['fieldName'];
            }
        }

        return $entities;
    }

    protected function isCachebleAssociation($name)
    {
        return isset($this->em->getClassMetadata($this->getEntityClassName())->getAssociationMappings()[$name]['cache']) ? true : false;
    }

    public function findOneById($id)
    {
        if (is_null($id)) {
            return;
        }

        $criteria = new Criteria(
            array(
                'comparison' => 'AND',
                'filters' =>
                    array(
                        array(
                            'type' => 'numeric',
                            'field' => $this->getEntityName() . '.id',
                            'value' => $id,
                            'comparison' => 'eq'
                        )
                    )
            )
        );

        return $this->findOneBy($criteria);
    }

    public function findOneBy(Criteria $criteria = null, Order $order = null, $limit = null, $offset = null)
    {
        return $this->getQuery($criteria, $order, $limit, $offset)->getOneOrNullResult();
    }

    /**
      * @return \Doctrine\ORM\Query
      */
    protected function getQuery(Criteria $criteria = null, Order $order = null, $limit = null, $offset = null)
    {
        $dql = $this->getDQL($criteria, $order);

        $query = $this->em->createQuery($dql)
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        if (!is_null($criteria)) {
            $query->setParameters($criteria->getParameters());
        }

        $this->setCacheable($query);

        return $query;
    }

    /**
     * Конфигурировать cache lifetime и имя region в наследуемых классах
     */
    protected function setCacheable(AbstractQuery $query)
    {
        $query->setCacheable($this->isCacheble());
    }

    protected function canWrite($entity, array $old_value = null)
    {
        

        return true;
    }
}
