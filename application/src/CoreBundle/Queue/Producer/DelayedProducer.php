<?php

namespace CoreBundle\Queue\Producer;

use OldSound\RabbitMqBundle\RabbitMq\Producer;
use PhpAmqpLib\Connection\AbstractConnection;

class DelayedProducer
{
    /**
     * @var AbstractConnection
     */
    protected $connection;

    /**
     * @var string
     */
    protected $destinationExchange;

    /**
     * @var string
     */
    protected $prefix;

    /**
     * DelayedProducer constructor.
     * @param AbstractConnection $connection
     * @param string $destinationExchange
     * @param string $prefix
     */
    public function __construct($connection, $destinationExchange, $prefix)
    {
        $this->connection = $connection;
        $this->destinationExchange = $destinationExchange;

        if (!is_string($prefix) || strlen($prefix) > 60) {
            throw new \UnexpectedValueException('Prefix should be a string of length <= 60.');
        }

        $this->prefix = $prefix;
    }

    /**
     * @param int $delay
     * @param string $msgBody
     * @param string $routingKey
     * @param array $additionalProperties
     */
    public function publish($delay, $msgBody, $routingKey = '', $additionalProperties = [])
    {
        if (!is_integer($delay) || $delay < 0) {
            throw new \UnexpectedValueException('Publish delay should be a positive integer.');
        }

        # expire the queue a little bit after the delay, but minimum 1 second
        $expiration = 1000 + floor(1.1 * $delay);

        $name = sprintf('%s-exchange', $this->prefix);
        $id = sprintf('%s-waiting-queue-%s-%d', $this->prefix, $routingKey, $delay);

        $producer = new Producer($this->connection);

        $producer->setExchangeOptions([
            'name' => $name,
            'type' => 'direct'
        ]);

        $producer->setQueueOptions([
            'name' => $id,
            'routing_keys' => [$id],
            'arguments' => [
                'x-message-ttl' => ['I', $delay],
                'x-dead-letter-exchange' => ['S', $this->destinationExchange],
                'x-dead-letter-routing-key' => ['S', $routingKey],
                'x-expires' => ['I', $expiration],
            ],
        ]);

        $producer->setupFabric();
        $producer->publish($msgBody, $id, $additionalProperties);
    }
}
