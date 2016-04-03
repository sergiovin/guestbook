<?php

namespace Application\ValueObject\Filter;

use Application\Contract\Criteria as CriteriaInterface;
use Application\ValueObject\Filter;
use Symfony\Component\Validator\Constraints as Assert;

class StringFilter extends Filter implements CriteriaInterface
{
    /**
     * @Assert\NotNull(message="""String filter, свойство сущности"" обязательно к заполнению")
     * @Assert\Regex(pattern="/^[\w\.]{2,}$/", message="""String filter, свойство сущности"" не соответствует формату")
     */
    protected $field;

    /**
     * @Assert\NotBlank(message="""String filter, значение"" обязательно к заполнению")
     */
    protected $value;

    public function __construct($field, $value)
    {
        $this->param_idx = self::$_param_idx;

        $this->field = $field;
        $this->value = $value;

        $this->condition = 'LOWER(' . $this->field . ') = LOWER(:' . $this->getParameterName($this->field) . ')';
    }

    public function getParameters()
    {
        return
            array(
                $this->getParameterName($this->field) => $this->value
            );
    }
}
