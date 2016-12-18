<?php

namespace CoreBundle\SNS\Instagram\Response;

use CoreBundle\SNS\Instagram\InstagramResponse;

class PostResponse extends InstagramResponse
{
    /**
     * PostResponse constructor.
     *
     * @param array $response
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
            throw new \Exception(sprintf('Instagram Post Request is Failed: %s', $e->getMessage()));
        }

        return $result;
    }
}
