<?php

namespace CoreBundle\SNS\Facebook;


use CoreBundle\SNS\AbstractResponse;

class FacebookResponse extends AbstractResponse
{
    /** @var  \Facebook\FacebookResponse */
    protected $response;

    /**
     * DealResponse constructor.
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
        if ($this->response->getHttpStatusCode() != (200)) {
            throw new \Exception(sprintf('Facebook Request is Failed: %s', $this->response->getData()->message));
        }

        $result = $this->response->getDecodedBody();

        if (is_object($result)) {
            $result = json_decode(json_encode($result), true);
        }

        return $result;
    }

    public function getGraphEdge()
    {
        if ($this->response->getHttpStatusCode() != (200)) {
            throw new \Exception(sprintf('Facebook Request is Failed: %s', $this->response->getData()->message));
        }

        return $this->response->getGraphEdge();
    }

    /**
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->response->getHttpStatusCode();
    }
}
