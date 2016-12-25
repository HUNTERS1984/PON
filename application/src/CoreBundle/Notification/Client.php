<?php

namespace CoreBundle\Notification;

use CoreBundle\Notification\AbstractDriver;
use CoreBundle\Notification\OneSignal\OneSignalDriver;

/**
 * Class Client
 * @package CoreBundle\Notification
 *
 * @method AbstractDriver send($message,array $segments = [], \DateTime $deliveryTime = null)
 */
class Client
{
    /**
     * @var AbstractDriver
     */
    private $driver;

    /**
     * @param AbstractDriver $driver
     */
    public function setDriver(AbstractDriver $driver)
    {
        $this->driver = $driver;
    }

    /**
     * Drivers will handler all stuffs. Let it do that for you.
     *
     * @param $method
     * @param $arguments
     *
     * @return mixed
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this->driver, $method)) {
            return call_user_func_array([$this->driver, $method], $arguments);
        }else{
            throw new \BadMethodCallException();
        }

    }
}