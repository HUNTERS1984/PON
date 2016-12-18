<?php

namespace CoreBundle\SNS\Facebook;

use CoreBundle\SNS\AbstractDriver;
use Facebook\Facebook;

class FacebookDriver extends AbstractDriver
{
    /**
     * @var Facebook
    */
    private $facebookManager;

    public function __construct(Facebook $manager)
    {
        $this->facebookManager = $manager;
    }

    /**
     * Get List Posts
     *
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @return array
     * @throws \Exception
     */
    public function listPost(\DateTime $from, \DateTime $to)
    {
        $service = new Post($this->getAccessToken(), $this->facebookManager);
        $service->setPrefix($this->getPrefix());

        return $service->listPost($from, $to);
    }
}
