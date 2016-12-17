<?php

namespace CoreBundle\SNS;

use CoreBundle\SNS\AbstractDriver;
use CoreBundle\SNS\Facebook\FacebookDriver;
use CoreBundle\SNS\Instagram\InstagramDriver;
use CoreBundle\SNS\Line\LineDriver;
use CoreBundle\SNS\Twitter\TwitterDriver;

/**
 * Class Client
 * @package CoreBundle\SNS
 *
 * @method AbstractDriver setTokenSecret($tokenSecret)
 * @method AbstractDriver setAccessToken($accessToken)
 * @method array listPost($params);
 */
class Client
{
    const FACEBOOK_TYPE = 1;
    const TWITTER_TYPE = 2;
    const INSTAGRAM_TYPE = 3;
    const LINE_TYPE = 4;

    /**
     * @var []AbstractDriver
     */
    private $drivers;

    /**
     * @var string
    */
    private $type;

    /**
     * @param AbstractDriver $driver
     */
    public function addDriver(AbstractDriver $driver)
    {
        $this->drivers[] = $driver;
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
        if (empty($this->drivers)) {
            throw new \RuntimeException("SNS Driver is not found.");
        }
        $result = [];
        foreach($this->drivers as $driver) {
            if($this->getDriverType($this->type)) {
                if (method_exists($driver, $method)) {
                    $result = call_user_func_array([$driver, $method], $arguments);
                    break;
                }else{
                    throw new \BadMethodCallException();
                }
            }
        }

        return $result;

    }

    public function getDriverType($type)
    {
        if($type == self::FACEBOOK_TYPE) {
            return FacebookDriver::class;
        }

        if($type == self::TWITTER_TYPE) {
            return TwitterDriver::class;
        }

        if($type == self::INSTAGRAM_TYPE) {
            return InstagramDriver::class;
        }

        if($type == self::LINE_TYPE) {
            return LineDriver::class;
        }
    }

    /**
     * @param string $type
     * @return Client
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }
}