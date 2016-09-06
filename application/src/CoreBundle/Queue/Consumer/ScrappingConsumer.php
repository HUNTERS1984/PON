<?php

namespace CoreBundle\Queue\Consumer;


use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class ScrappingConsumer implements ConsumerInterface
{

    public function __construct()
    {
    }

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
}