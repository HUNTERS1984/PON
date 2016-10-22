<?php

namespace CoreBundle\Serializator;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer as BaseSerializer;

class Serializer
{
    /**
     * @var BaseSerializer $serialize
    */
    protected $serialize;

    /**
     * constructor
     *
     * @param BaseSerializer $serialize
    */
    public function __construct(BaseSerializer $serialize)
    {
        $this->serialize = $serialize;
    }

    public function serialize($entity, $groups)
    {
        if (!is_array($groups)) {
            $groups = [$groups];
        }
        $context = SerializationContext::create()->setGroups($groups);
        return json_decode($this->serialize->serialize(
            $entity,
            'json',
            $context->setSerializeNull(true)
        ), true);
    }
}