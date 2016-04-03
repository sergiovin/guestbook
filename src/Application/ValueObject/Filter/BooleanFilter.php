<?php

namespace Application\ValueObject\Filter;

use Application\Contract\Criteria as CriteriaInterface;
use Application\ValueObject\Filter;
use Symfony\Component\Validator\Constraints as Assert;

class BooleanFilter extends Filter implements CriteriaInterface
{
    /**
     * @Assert\NotNull(message="""Boolean filter, свойство сущности"" обязательно к заполнению")
     * @Assert\Regex(pattern="/^[\w\.]{2,}$/", message="""Boolean filter, свойство сущности"" не соответствует формату")
     */
    protected $field;

    /**
     * @Assert\Choice(choices={"true", "false"}, message="""Boolean filter"" - возможные значения: ""true"", ""false""")
     */
    protected $value;

    public function __construct($field, $value)
    {
        $this->param_idx = self::$_param_idx;

        $this->field = $field;
        $this->value = $value;

        $this->condition = $this->field . ' = :' . $this->getParameterName($this->field);
    }

    public function getParameters()
    {
        return
            array(
                $this->getParameterName($this->field) => $this->value
            );
    }
}
