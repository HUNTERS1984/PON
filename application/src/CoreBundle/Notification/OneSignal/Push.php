<?php

namespace CoreBundle\Notification\OneSignal;

use CoreBundle\Notification\AbstractPush;
use CoreBundle\Notification\OneSignal\Request\PushRequest;
use GuzzleHttp\Client;

class Push extends AbstractPush
{
    /**
     * @var PushRequest
     */
    protected $request;

    /**
     * Construct
     *
     * @param Client $client
     * @param string $apiKey
     * @param string $appId
     */
    public function __construct(Client $client, $apiKey, $appId)
    {
        $this->initialize($client, $apiKey, $appId);
    }

    /**
     * Initialize the Push
     *
     * @param Client $client
     * @param string $apiKey
     * @param string $appId
     */
    public function initialize(Client $client, $apiKey, $appId)
    {
        $this->request = new PushRequest($client, $apiKey, $appId);
    }

    /**
     * Send Push
     *
     * @param string $message
     * @param array $segments
     * @param \DateTime $deliveryTime
     * @return mixed
     */
    public function send($message, array $segments = [], \DateTime $deliveryTime = null)
    {
        $response = $this->request
            ->setDeliveryTime($deliveryTime)
            ->setMessage($message)
            ->setSegments($segments)
            ->send();

        return $response->getResult();
    }
}