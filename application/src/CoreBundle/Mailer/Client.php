<?php

namespace CoreBundle\Mailer;

use CoreBundle\Mailer\AbstractDriver;
use CoreBundle\Mailer\Swift\SwiftDriver;

/**
 * Class Client
 * @package CoreBundle\Mailer
 *
 * @method AbstractDriver sendEmail($subject, $sender, $recipient, $body, $senderName, array $bcc, $replyTo)
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