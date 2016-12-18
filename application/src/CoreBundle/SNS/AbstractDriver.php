<?php

namespace CoreBundle\SNS;

abstract class AbstractDriver
{
    /**
     * @var string
    */
    private $accessToken;

    /**
     * @var string
    */
    private $tokenSecret;

    /**
     * @var string
    */
    private $prefix;

    /**
     * Get Posts
     *
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @return array
     */
    abstract public function listPost(\DateTime $from, \DateTime $to);

    /**
     * @param string $accessToken
     * @return AbstractDriver
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @param string $tokenSecret
     * @return AbstractDriver
     */
    public function setTokenSecret($tokenSecret)
    {
        $this->tokenSecret = $tokenSecret;
        return $this;
    }

    /**
     * @return string
     */
    public function getTokenSecret()
    {
        return $this->tokenSecret;
    }

    /**
     * @param string $prefix
     * @return $this
     */
    public function setPrefix(string $prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }
}
