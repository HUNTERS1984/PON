<?php

namespace CoreBundle\SNS\Twitter;


use CoreBundle\SNS\AbstractResponse;

class TwitterResponse extends AbstractResponse
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
        if(isset($this->response->errors) && count($this->response->errors) > 0) {
            throw new \Exception(sprintf('Twitter Request is Failed: %s',  $this->response->errors[0]->message));
        }
        $result = $this->response;

        return $result;
    }

    /**
     * @return integer
     */
    public function getStatusCode()
    {
        if(isset($this->response->errors) && count($this->response->errors) > 0) {
           return 500;
        }
        return 200;
    }
}
