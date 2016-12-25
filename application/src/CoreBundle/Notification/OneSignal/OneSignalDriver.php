<?php

namespace CoreBundle\Notification\OneSignal;


use CoreBundle\Notification\AbstractDriver;
use GuzzleHttp\Client;

class OneSignalDriver extends AbstractDriver
{
    /**
     * @var Client
     */
    private $oneSingalManager;

    /**
     * @var string
    */
    private $apiKey;

    /**
     * @var string
     */
    private $appId;

    public function __construct(Client $manager, $apiKey, $appId)
    {
        $this->oneSingalManager = $manager;
        $this->apiKey = $apiKey;
        $this->appId = $appId;
    }

    /**
     * Send Push
     *
     * @param string $message
     * @param array $segments
     * @param \DateTime $deliveryTime
     *
     * @return mixed
     */
    public function send($message,array $segments = [], \DateTime $deliveryTime = null)
    {
        $service = new Push($this->oneSingalManager, $this->apiKey, $this->appId);
        return $service->send($message,$segments, $deliveryTime);
    }
}
