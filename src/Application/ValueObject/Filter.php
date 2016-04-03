<?php

namespace Application\ValueObject;

use Application\Contract\Criteria as CriteriaInterface;
use Application\Exception\BadRequestException;

class Filter implements CriteriaInterface
{
    protected static $_param_idx = 0;

    protected $param_idx;

    public function __construct($condition)
    {
        $this->condition = $condition;
    }

    public static function create($type, $field, $value = null, $comparison = null)
    {
        self::$_param_idx++;

        switch ($type) {
            case 'boolean':
                return new Filter\BooleanFilter($field, $value);
                break;
            case 'date':
                return new Filter\DateFilter($field, $value, $comparison);
                break;
            case 'json':
                return new Filter\JsonFilter($field, $value, $comparison);
                break;
            case 'list':
                return new Filter\ListFilter($field, $value, $comparison);
                break;
            case 'null':
                return new Filter\NullFilter($field, $comparison);
                break;
            case 'numeric':
                return new Filter\NumericFilter($field, $value, $comparison);
                break;
            case 'string':
                return new Filter\StringFilter($field, $value);
                break;
            default:
                throw new BadRequestException('Фильтра "' . $type . '" не существует.');
                break;
        }
    }

    public function getCondition()
    {
        return $this->condition;
    }

    public function getParameters()
    {
        return;
    }

    protected function getParameterName($field)
    {
        return str_replace('.', '_', $field) . $this->param_idx;
    }
}
