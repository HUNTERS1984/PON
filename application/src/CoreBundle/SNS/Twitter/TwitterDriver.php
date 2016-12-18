<?php

namespace CoreBundle\SNS\Twitter;


use Abraham\TwitterOAuth\TwitterOAuth;
use CoreBundle\SNS\AbstractDriver;

class TwitterDriver extends AbstractDriver
{
    /**
     * @var TwitterOAuth
     */
    private $twitterManager;

    public function __construct(TwitterOAuth $manager)
    {
        $this->twitterManager = $manager;
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
        $service = new Post($this->getAccessToken(), $this->getTokenSecret(), $this->twitterManager);
        $service->setPrefix($this->getPrefix());

        return $service->listPost($from, $to);
    }
}
