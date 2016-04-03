<?php

namespace InfrastructureBundle\Adapter;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;

class Serializer
{
    protected $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function serialize($data, $format, $groups = array())
    {
        $context = new SerializationContext();
        $context->setSerializeNull(true);

        if ($groups) {
            $context->setGroups($groups);
        }

        return $this->serializer->serialize($data, $format, $context);
    }
}
