<?php

namespace CoreBundle\Manager;


use CoreBundle\Analytics\Client;
use Monolog\Logger;

class AnalyticsManager
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var string
    */
    private $metrics;

    /**
     * @var Client
    */
    protected $client;

    /**
     * @param mixed $client
     * @return AnalyticsManager
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    public function listReport($dimensions)
    {
        try {
            return $this->client->listReport($dimensions, $this->metrics);
        }catch (\Exception $ex) {
            $this->logger->error($ex->getTraceAsString());
            $this->logger->error(sprintf("Analytics Manager: %s",$ex->getMessage()));
            return null;
        }

    }

    /**
     * @param Logger $logger
     * @return AnalyticsManager
     */
    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;

        return $this;
    }

    /**
     * @param string $metrics
     * @return AnalyticsManager
     */
    public function setMetrics($metrics)
    {
        $this->metrics = $metrics;

        return $this;
    }
}