<?php

namespace InfrastructureBundle\Repository;

use Application\Contract\RepositoryManager as RepositoryManagerInterface;
use Application\Contract\Translator;
use Application\ValueObject\Criteria;
use Application\ValueObject\Order;
use Domain\Contract\RepositoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RepositoryManager implements RepositoryManagerInterface
{
    private $container;
    private $mapping;

    public function __construct(ContainerInterface $container)
    {
         $this->container = $container;
         $this->mapping = array();
    }

    public function getConnection()
    {
        return $this->container->get('doctrine')->getManager()->getConnection();
    }

    public function addMapping($className, $service)
    {
        $entityName = str_replace(['InfrastructureBundle\\Repository\\', 'Repository'], ['',''], $className);

        $this->mapping[$entityName] = $service;
    }

    /**
     * @param string $entity
     * The entity name to search repository for
     * @return entity repository service
     */
    public function get($entity)
    {
        return $this->resolveByEntityName($entity);
    }

    private function resolveByEntityName($entityName)
    {
        if (isset($this->mapping[$entityName]) && $this->container->has($this->mapping[$entityName])) {

            $repository = $this->container->get($this->mapping[$entityName]);

            if ($repository instanceof RepositoryInterface) {
                return $repository;
            }

            throw new \LogicException('Repository '.get_class($repository).' must be instance of Domain\\Contract\\RepositoryInterface');
        }

        throw new \LogicException('Repository for entity class '.$entityName.' not found or not registered by infrastructure.entity_repository tag name');
    }

    /**
    * @param string $entity
    * The entity name to search repository for
    * @param int $id
    * Primary key for findById method
    * @return entity object
    */
    public function findOneById($entity, $id)
    {
        $repository = $this->resolveByEntityName($entity);

        return $repository->findOneById($id);
    }

    public function findOneBy($entity, Criteria $criteria, Order $order = null, $limit = null, $offset = null)
    {
        return $this->resolveByEntityName($entity)->findOneBy($criteria, $order, $limit, $offset);
    }

    public function getResult($entity, Criteria $criteria = null, Order $order = null, $limit = null, $offset = null)
    {
        return $this->resolveByEntityName($entity)->getResult($criteria, $order, $limit, $offset);
    }

    public function getArrayResult($entity, Criteria $criteria, Order $order = null, $limit = null, $offset = null)
    {
        return $this->resolveByEntityName($entity)->getResult($criteria, $order, $limit, $offset);
    }

    /**
    * @param obect $entity
    * The entity object to persist
    */
    public function persist($entity)
    {
        $className = $this->getClassName($entity);

        $repository = $this->resolveByEntityName($className);

        if ($repository instanceof RepositoryInterface) {
            return $repository->persist($entity);
        }

        throw new \LogicException('Repository   '.get_class($repository).' must be instance of Domain\\Contract\\RepositoryInterface');
    }

    public static function getClassName($entity)
    {
        if (preg_match('/Domain\\\\Entity\\\\(.+)$/', get_class($entity), $matches)) {
            return $matches[sizeof($matches) - 1];
        }

        throw new \LogicException('Classname incorrect ' . get_class($entity));
    }

    /**
    * @param obect $entity
    * The entity object to remove
    */
    public function remove($entity)
    {
        $className = str_replace('Domain\\Entity\\', '', get_class($entity));

        return $this->resolveByEntityName($className)->remove($entity);
    }

    public function flush()
    {
        $this->container->get('doctrine.orm.entity_manager')->flush();
    }

}
