<?php

namespace CoreBundle\Analytics\Google\Response;

use CoreBundle\Analytics\Google\GoogleResponse;

class ReportResponse extends GoogleResponse
{
    /**
     * @return \Google_Service_Analytics_GaData
     * @throws \Exception
     */
    public function getResult()
    {
        return $this->response;
    }

}
