<?php

namespace CoreBundle\SNS;

abstract class AbstractResponse
{
    /**
     * @return array
     */
    abstract public function getResult();

    /**
     * @return integer
     */
    abstract public function getStatusCode();
}
