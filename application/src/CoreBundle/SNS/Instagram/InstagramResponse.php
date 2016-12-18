<?php

namespace CoreBundle\SNS\Instagram;


use CoreBundle\SNS\AbstractResponse;

class InstagramResponse extends AbstractResponse
{

    /**
     * @var  array|object
     */
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
        if ($this->response->meta->code != 200) {
            throw new \Exception(sprintf('Instagram Request is Failed: %s', $this->response->meta->code));
        }

        $result = $this->response->data;

        return $result;
    }

    /**
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->response->meta->code;
    }
}
