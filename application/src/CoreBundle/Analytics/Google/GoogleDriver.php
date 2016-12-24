<?php

namespace CoreBundle\Analytics\Google;


use CoreBundle\Analytics\AbstractDriver;

class GoogleDriver extends AbstractDriver
{
    /**
     * @var \Google_Service_Analytics
     */
    private $googleAnalyticsManager;

    public function __construct(\Google_Service_Analytics $manager)
    {
        $this->googleAnalyticsManager = $manager;
    }

    /**
     * List Report
     *
     * @param string $dimensions
     * @param string $metrics
     * @return mixed
     */
    public function listReport($dimensions, $metrics)
    {
        $service = new Report($this->googleAnalyticsManager);
        return $service->listReport($dimensions, $metrics);
    }
}
