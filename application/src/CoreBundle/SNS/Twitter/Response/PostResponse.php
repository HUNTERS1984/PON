<?php

namespace CoreBundle\SNS\Twitter\Response;

use CoreBundle\SNS\Twitter\TwitterResponse;

class PostResponse extends TwitterResponse
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
            throw new \Exception(sprintf('Twitter Post Request is Failed: %s', $e->getMessage()));
        }

        return $result;
    }
}
