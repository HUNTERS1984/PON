<?php

namespace CoreBundle\Notification;

abstract class AbstractRequest
{

    /**
     * Send Push
     *
     * @return mixed
     */
    abstract public function send();
}
