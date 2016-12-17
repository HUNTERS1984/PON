<?php

namespace CoreBundle\SNS\Facebook;

use CoreBundle\SNS\AbstractRequest;

abstract class FacebookRequest extends AbstractRequest
{
    /**
     * @var array
     */
    protected $properties;

    /**
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param array $properties
     *
     * @return $this
     */
    public function setProperties($properties)
    {
        $this->properties = $properties;

        return $this;
    }

}
