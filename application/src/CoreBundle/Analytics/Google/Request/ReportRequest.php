<?php

namespace CoreBundle\Analytics\Google\Request;

use CoreBundle\Analytics\Google\Response\ReportResponse;
use CoreBundle\Analytics\Google\GoogleRequest;

class ReportRequest extends GoogleRequest
{
    /**
     * @var \Google_Service_Analytics
     */
    protected $client;

    /**
     * @var string
    */
    protected $dimensions;

    /**
     * @var string $metrics
    */
    protected $metrics;

    /**
     * ReportRequest constructor.
     *
     * @param \Google_Service_Analytics $client
     */
    public function __construct(\Google_Service_Analytics $client)
    {
        $this->client = $client;
    }

    /**
     * List Report
     *
     * @return ReportResponse
     * @throws \Exception
     */
    public function listReport()
    {
        try {
            $profileId = $this->getFirstProfileId($this->client);
            //,ga:users,ga:pageviews,ga:avgSessionDuration,ga:bounceRate,ga:exitRate
            //ga:sessions,ga:users,ga:pageviews,ga:avgSessionDuration,ga:bounceRate,ga:exitRate
            //ga:date,ga:hour,ga:nthWeek
            //'ga:sessions,ga:users,ga:pageviews,ga:avgSessionDuration,ga:bounceRate,ga:exitRate'
            $response = $this->client->data_ga->get(
                'ga:' . $profileId,
                '30daysAgo',
                'today',
                $this->getMetrics(),
                [
                    'dimensions'=> $this->getDimensions(),
                    'output'=> 'datatable'
                ]
            );
            return new ReportResponse($response);
        } catch(\Exception $e) {
            throw new \Exception(sprintf("GoogleAnalytics Driver With ErrorCode: %s and Message Code %s", $e->getCode(), $e->getMessage()));
        }
    }

    function getFirstProfileId($analytics) {
        // Get the user's first view (profile) ID.

        // Get the list of accounts for the authorized user.
        $accounts = $analytics->management_accounts->listManagementAccounts();

        if (count($accounts->getItems()) > 0) {
            $items = $accounts->getItems();
            $firstAccountId = $items[0]->getId();

            // Get the list of properties for the authorized user.
            $properties = $analytics->management_webproperties
                ->listManagementWebproperties($firstAccountId);

            if (count($properties->getItems()) > 0) {
                $items = $properties->getItems();
                $firstPropertyId = $items[0]->getId();

                // Get the list of views (profiles) for the authorized user.
                $profiles = $analytics->management_profiles
                    ->listManagementProfiles($firstAccountId, $firstPropertyId);

                if (count($profiles->getItems()) > 0) {
                    $items = $profiles->getItems();

                    // Return the first view (profile) ID.
                    return $items[0]->getId();

                } else {
                    throw new Exception('No views (profiles) found for this user.');
                }
            } else {
                throw new Exception('No properties found for this user.');
            }
        } else {
            throw new Exception('No accounts found for this user.');
        }
    }

    /**
     * @param string $dimensions
     * @return ReportRequest
     */
    public function setDimensions($dimensions)
    {
        $this->dimensions = $dimensions;

        return $this;
    }

    /**
     * @return string
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }

    /**
     * @param string $metrics
     * @return ReportRequest
     */
    public function setMetrics($metrics)
    {
        $this->metrics = $metrics;

        return $this;
    }

    /**
     * @return string
     */
    public function getMetrics()
    {
        return $this->metrics;
    }
}
