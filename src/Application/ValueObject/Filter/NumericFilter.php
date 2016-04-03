<?php

namespace Application\ValueObject\Filter;

use Application\Contract\Criteria as CriteriaInterface;
use Application\ValueObject\Filter;
use Symfony\Component\Validator\Constraints as Assert;

class NumericFilter extends Filter implements CriteriaInterface
{
    /**
     * @Assert\NotNull(message="""Numeric filter, свойство сущности"" обязательно к заполнению")
     * @Assert\Regex(pattern="/^[\w\.]{2,}$/", message="""Numeric filter, свойство сущности"" не соответствует формату")
     */
    protected $field;

    /**
     * @Assert\NotBlank(message="""Numeric filter, значение"" обязательно к заполнению")
     * @Assert\Type(type="numeric", message="""Numeric filter, значение"" не соответствует формату")
     */
    protected $value;

    /**
     * @Assert\Choice(choices={"=", "!=", "<", ">", "<=", ">="}, message="""Numeric filter"" - возможные значения: ""="", ""!="", ""<"", "">""")
     */
    protected $comparison;

    public function __construct($field, $value, $comparison)
    {
        $this->param_idx = self::$_param_idx;

        $this->field = $field;
        $this->value = $value;

        switch ($comparison) {
            case 'eq':
                $this->comparison = '=';
                break;
            case 'neq':
                $this->comparison = '!=';
                break;
            case 'lt':
                $this->comparison = '<';
                break;
            case 'gt':
                $this->comparison = '>';
                break;
            default:
                $this->comparison = $comparison;
                break;

        }

        $this->condition = $this->field . ' ' . $this->comparison . ' :' . $this->getParameterName($this->field);
    }

    public function getParameters()
    {
        return
            array(
                $this->getParameterName($this->field) => $this->value
            );
    }
}
