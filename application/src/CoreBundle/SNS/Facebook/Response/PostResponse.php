<?php

namespace CoreBundle\SNS\Facebook\Response;

use CoreBundle\SNS\Facebook\FacebookResponse;

class PostResponse extends FacebookResponse
{
    /**
     * PostResponse constructor.
     *
     * @param \Facebook\FacebookResponse $response
     */
    public function __construct($response)
    {
        parent::__construct($response);
    }

    /**
     * {@inheritdoc}
     */
    public function getResult()
    {
        try {
            $result = parent::getResult();
        } catch (\Exception $e) {
            throw new \Exception(sprintf('Facebook Post Request is Failed: %s', $e->getMessage()));
        }

        return $result;
    }
}
