<?php

namespace InfrastructureBundle\Repository;

use Application\Contract\Translator;
use Application\Contract\User;
use Application\ValueObject\Criteria;
use Application\ValueObject\Order;
use Doctrine\Common\Cache\Cache;
use Doctrine\ORM\EntityManager;

abstract class AbstractTreeRepository extends AbstractRepository
{
    /**
    * @var \Gedmo\Tree\Entity\Repository\NestedTreeRepository
    */
    public $repository;

    public function __construct(EntityManager $em, User $user, Cache $cache, Translator $translator)
    {
        parent::__construct($em, $user, $cache, $translator);

        $this->repository->setChildrenIndex('children');
    }

    public function getArrayResult(Criteria $criteria = null, Order $order = null, $limit = null, $offset = null)
    {
        return $this->getQuery($criteria, $order)->getArrayResult();
    }

    public function getPaginatorResult(Criteria $criteria = null, Order $order = null, $limit = null, $offset = null)
    {
        $nodes = $this->getResult($criteria, $order, $limit, $offset);

        $data = array();
        $total = count($nodes);

        while (!empty($nodes)) {
            $root = $this->getRoot($nodes);

            $this->fillChildren($root, $nodes);
            $data[] = $root;
        }

        return array(
            'data' => $data,
            'total' => $total
        );
    }

    public function getResult(Criteria $criteria = null, Order $order = null, $limit = null, $offset = null)
    {
        return $this->getQuery($criteria, $order)->getResult();
    }

    private function getRoot(&$nodes)
    {
        //получаем id доступных пользователю записей
        foreach ($nodes as $node) {
            $ids[] = $node->getId();
        }

        //получаем корневой элемент
        foreach ($nodes as $i => $node) {
            if (!in_array($node->getParentId(), $ids)) {
                unset($nodes[ $i ]);

                return $node;
            }
        }
    }

    private function fillChildren($root, &$nodes)
    {
        foreach ($nodes as $i => $node) {
            if ($root->getId() === $node->getParentId()) {
                $root->getChildren()->add($node);

                unset($nodes[ $i ]);
            }
        }

        $root->getChildren()->setInitialized(true);

        foreach ($root->getChildren() as $node) {
            $this->fillChildren($node, $nodes);
        }
    }
}
