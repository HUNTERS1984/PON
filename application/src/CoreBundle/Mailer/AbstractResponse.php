<?php

namespace CoreBundle\Mailer;

abstract class AbstractResponse
{
    /**
     * @return mixed
     */
    abstract public function getResult();
}
