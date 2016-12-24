<?php

namespace CoreBundle\Analytics\Google;


use CoreBundle\Analytics\AbstractResponse;

class GoogleResponse extends AbstractResponse
{

    /** @var  mixed */
    protected $response;

    /**
     * GoogleResponse constructor.
     *
     * @param mixed $response
     */
    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function getResult()
    {
        return $this->response;
    }
}
