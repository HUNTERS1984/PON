<?php

namespace CoreBundle\Notification;

abstract class AbstractDriver
{
    /**
     * Send Push
     *
     * @param string $message
     * @param array $segments
     * @param \DateTime $deliveryTime
     *
     * @return mixed
     */
    abstract public function send($message,array $segments = [], \DateTime $deliveryTime = null);
}
