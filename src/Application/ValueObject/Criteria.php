<?php

namespace Application\ValueObject;

use Application\Adapter\ArrayCollection;
use Application\Contract\Criteria as CriteriaInterface;
use Application\Exception\BadRequestException;
use Symfony\Component\Validator\Constraints as Assert;

class Criteria implements CriteriaInterface
{
    private $comparison;

    /**
    * @Assert\Valid
    */
    private $filters;

    public function __construct(array $params = null)
    {
        if (isset($params['comparison'])) {
            $this->comparison = $params['comparison'];
        }

        $this->filters = new ArrayCollection();

        if (isset($params['filters'])) {
            switch (gettype($params['filters'])) {
                case 'array':
                    $this->setFiltersFromArray($params['filters']);
                    break;
                case 'object':
                    $this->setFiltersFromObject($params['filters']);
                    break;
            }
        }
    }

    protected function setFiltersFromArray(array $filters)
    {
        $this->filters->clear();

        foreach ($filters as $filter) {

            $result = null;

            //проверяем определены ли все поля
            if (isset($filter['type']) && isset($filter['field'])) {

                if (!isset($filter['value'])) {
                    $filter['value'] = null;
                }

                if (!isset($filter['comparison'])) {
                    $filter['comparison'] = null;
                }

                $result = Filter::create(
                    $filter['type'],
                    $filter['field'],
                    $filter['value'],
                    $filter['comparison']
                );

            //проверяем не являются ли критерии вложенными
            } elseif (isset($filter['criteria']) && isset($filter['criteria']['filters']) && is_array($filter['criteria']['filters'])) {

                $result = new Criteria($filter['criteria']);

            } else {

                throw new BadRequestException(json_encode($filter));

            }

            $this->addFilter($result);
        }
    }

    public function addFilter(CriteriaInterface $filter)
    {
        $this->filters->add($filter);
    }

    protected function setFiltersFromObject(ArrayCollection $filters)
    {
        $this->filters = $filters;
    }

    public static function addPermission(Criteria $criteria = null, $condition)
    {
        if ($condition) {
            $params['comparison'] = 'AND';
            $params['filters'] = new ArrayCollection(
                array(
                    new Filter($condition)
                )
            );

            if (!is_null($criteria)) {
                $params['filters']->add($criteria);
            }

            return new Criteria($params);
        }

        return $criteria;
    }

    public function getCondition($where = '')
    {
        $result = array();

        foreach ($this->filters as $filter) {
            $result[] = $filter->getCondition();
        }
        return (empty($result)) ? '' : $where . '(' . implode(' ' . $this->comparison . ' ', $result) . ')';
    }

    public function getParameters()
    {
        $result = array();

        foreach ($this->filters as $filter) {
            $parameters = $filter->getParameters();
            if (is_array($parameters)) {
                $result = array_merge($result, $parameters);
            }
        }

        return $result;
    }
}
