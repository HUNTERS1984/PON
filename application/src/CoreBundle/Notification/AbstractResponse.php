<?php

namespace CoreBundle\Notification;

abstract class AbstractResponse
{
    /**
     * @return mixed
     */
    abstract public function getResult();
}
