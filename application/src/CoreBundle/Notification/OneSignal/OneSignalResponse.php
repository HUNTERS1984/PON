<?php

namespace CoreBundle\Notification\OneSignal;


use CoreBundle\Notification\AbstractResponse;
use GuzzleHttp\Psr7\Response;

class OneSignalResponse extends AbstractResponse
{

    /** @var  Response */
    protected $response;

    /**
     * OneSignalResponse constructor.
     *
     * @param mixed $response
     */
    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getResult()
    {
        if ($this->response->getStatusCode() != (200)) {
            throw new \Exception(sprintf('OneSignal Request is Failed: %s', $this->response->getReasonPhrase()));
        }

        $result = json_decode($this->response->getBody()->getContents(), true);

        if(isset($result['errors'][0])) {
            throw new \Exception(sprintf('OneSignal Request is Failed: %s', $result['errors'][0]));
        }

        return $result;
    }

    /**
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }
}
