<?php

namespace Application\ValueObject\Filter;

use Application\Contract\Criteria as CriteriaInterface;
use Application\ValueObject\Filter;
use Symfony\Component\Validator\Constraints as Assert;

class ListFilter extends Filter implements CriteriaInterface
{
    /**
     * @Assert\NotNull(message="""List filter, свойство сущности"" обязательно к заполнению")
     * @Assert\Regex(pattern="/^[\w\.]{2,}$/", message="""List filter, свойство сущности"" не соответствует формату")
     */
    protected $field;

    /**
     * @Assert\Count(min="1", minMessage="""List filter, значение"" необходимо указать как минимум одно значение")
     */
    protected $value;

    /**
     * @Assert\Choice(choices={"IN", "NOT IN"}, message="""List filter"" - возможные значения: ""true"", ""false""")
     */
    protected $comparison;

    public function __construct($field, $value, $comparison)
    {
        $this->param_idx = self::$_param_idx;

        $this->field = $field;
        $this->value = (is_array($value)) ? $value : explode(',', $value);

        switch ($comparison) {
            case 'neq':
                $this->comparison = 'NOT IN';
                break;
            default:
                $this->comparison = 'IN';
                break;
        }

        $this->condition = $this->field . ' '. $this->comparison . ' (:' . $this->getParameterName($this->field) . ')';
    }

    public function getParameters()
    {
        return
            array(
                $this->getParameterName($this->field) => $this->value
            );
    }
}
