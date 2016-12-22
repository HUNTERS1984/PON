<?php

namespace CoreBundle\Mailer\Swift;


use CoreBundle\Mailer\AbstractResponse;

class SwiftResponse extends AbstractResponse
{

    /** @var  mixed */
    protected $response;

    /**
     * SwiftResponse constructor.
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
        return $this->response;
    }
}
