<?php

namespace CoreBundle\SNS\Instagram;


use CoreBundle\SNS\AbstractDriver;
use MetzWeb\Instagram\Instagram;

class InstagramDriver extends AbstractDriver
{
    /**
     * @var Instagram
     */
    private $instagramManager;

    public function __construct(Instagram $manager)
    {
        $this->instagramManager = $manager;
    }

    /**
     * Get Posts
     *
     * @param \DateTime $from
     * @param \DateTime $to
     *
     * @return array
     */
    public function listPost(\DateTime $from, \DateTime $to)
    {
        $service = new Post($this->getAccessToken(), $this->instagramManager);
        $service->setPrefix($this->getPrefix());

        return $service->listPost($from, $to);
    }
}
