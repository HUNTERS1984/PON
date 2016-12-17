<?php

namespace CoreBundle\Queue\Consumer;

use Monolog\Logger;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class SyncPostConsumer implements ConsumerInterface
{

    /**
     * @var Logger
    */
    private $logger;

    /**
     * {@inheritdoc}
     */
    public function execute(AMQPMessage $msg)
    {
        $data = unserialize($msg->getBody());
        try {
           
        } catch (\Exception $e) {

        }
    }

    /**
     * @param Logger $logger
     * @return ScrappingConsumer
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
        return $this;
    }
}