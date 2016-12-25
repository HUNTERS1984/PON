<?php

namespace CoreBundle\Manager;


use CoreBundle\Notification\Client;
use Monolog\Logger;

class NotificationManager
{
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var Client
    */
    protected $client;

    /**
     * @param Client $client
     * @return NotificationManager
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Send PushNotification
     *
     * @param string $message
     * @param array $segments
     * @param \DateTime $deliveryTime
     *
     * @throws \Exception
     * @return array | null
    */
    public function send($message, array $segments = [], \DateTime $deliveryTime = null)
    {
        try {
            return $this->client->send($message, $segments, $deliveryTime);
        }catch (\Exception $ex) {
            $this->logger->error($ex->getTraceAsString());
            $this->logger->error(sprintf("Notification Manager: %s",$ex->getMessage()));
            throw $ex;
        }

    }

    /**
     * @param Logger $logger
     * @return NotificationManager
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;

        return $this;
    }
}