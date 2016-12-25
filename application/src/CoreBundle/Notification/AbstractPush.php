<?php

namespace CoreBundle\Notification;

abstract class AbstractPush
{
    /**
     * Send Push
     *
     * @param string $message
     * @param array $segments
     * @param \DateTime $deliveryTime
     * @return mixed
     */
    abstract public function Send($message, array $segments = [], \DateTime $deliveryTime = null);
}
