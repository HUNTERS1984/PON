<?php

namespace CoreBundle\EventListener;

use CoreBundle\Event\NotificationEvents;
use CoreBundle\Manager\NotificationManager;
use Monolog\Logger;
use OldSound\RabbitMqBundle\RabbitMq\Producer;

class NotificationListener
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var array
    */
    private $segments;

    /**
     * @var NotificationManager
     */
    protected $notificationManager;

    /**
     * @param NotificationEvents $eventArgs
     *
     * @throws \Exception
     */
    public function preCreate($eventArgs)
    {
        $pushSetting = $eventArgs->getPushSetting();
        try {
            $segments[] = $this->segments[$pushSetting->getSegment()];
            $deliveryTime = null;
            if($pushSetting->getType() == 'special') {
                $deliveryTime = $pushSetting->getDeliveryTime();
            }
            $result = $this->notificationManager->send($pushSetting->getMessage(), $segments, $deliveryTime);

            $pushSetting
                ->setNotificationId($result['id'])
                ->setRecipientTotal($result['recipients'])
                ->setStatus(1);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
            $this->logger->error($e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * @param NotificationEvents $eventArgs
     */
    public function postCreate($eventArgs)
    {

    }

    /**
     * @param Logger $logger
     *
     * @return NotificationListener
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param NotificationManager $notificationManager
     * @return NotificationListener
     */
    public function setNotificationManager(NotificationManager $notificationManager)
    {
        $this->notificationManager = $notificationManager;

        return $this;
    }

    /**
     * @param array $segments
     * @return NotificationListener
     */
    public function setSegments($segments)
    {
        $this->segments = $segments;

        return $this;
    }
}
