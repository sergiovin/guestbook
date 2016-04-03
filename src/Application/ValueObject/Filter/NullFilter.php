<?php

namespace Application\ValueObject\Filter;

use Application\Contract\Criteria as CriteriaInterface;
use Application\ValueObject\Filter;
use Symfony\Component\Validator\Constraints as Assert;

class NullFilter extends Filter implements CriteriaInterface
{
    /**
     * @Assert\NotNull(message="""Null filter, свойство сущности"" обязательно к заполнению")
     * @Assert\Regex(pattern="/^[\w\.]{2,}$/", message="""Null filter, свойство сущности"" не соответствует формату")
     */
    protected $field;

    /**
     * @Assert\Choice(choices={"IS NULL", "IS NOT NULL"}, message="""Null filter"" - возможные значения: ""IS NULL"", ""IS NOT NULL""")
     */
    protected $comparison;

    public function __construct($field, $comparison)
    {
        $this->param_idx = self::$_param_idx;

        $this->field = $field;
        $this->value = null;

        switch ($comparison) {
            case 'eq':
                $this->comparison = 'IS NULL';
                break;
            case 'neq':
                $this->comparison = 'IS NOT NULL';
                break;
        }

        $this->condition = $this->field . ' ' . $this->comparison ;
    }
}
