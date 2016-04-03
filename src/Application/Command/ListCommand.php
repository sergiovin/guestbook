<?php

namespace Application\Command;

use Application\Adapter\ArrayCollection;
use Application\ValueObject;
use Symfony\Component\Validator\Constraints as Assert;

abstract class ListCommand extends AbstractCommand
{
    /**
    * @Assert\Valid
    */
    protected $criteria = null;

    protected $order = null;

    /**
     * @Assert\Regex(pattern="/^\d{1,}$/", message="""limit"" должен содержать только цифры")
     */
    protected $limit = null;

    /**
     * @Assert\Regex(pattern="/^\d{1,}$/", message="""offset"" должен содержать только цифры")
     */
    protected $offset = null;

    public function __construct($criteria, $order, $limit, $offset = 0)
    {
        if (is_array($criteria) && !empty($criteria)) {
            $this->criteria = new ValueObject\Criteria($criteria);
        }
        if (is_array($order) && !empty($order)) {
            $this->order = new ValueObject\Order($order);
        }

        $this->limit = $limit;
        $this->offset = $offset;
    }

    public function getData()
    {
        return new ArrayCollection(
            array(
                'criteria' => $this->criteria,
                'order'    => $this->order,
                'limit'    => $this->limit,
                'offset'   => $this->offset
            )
        );
    }
}
