<?php

namespace CoreBundle\SNS\Instagram\Request;

use CoreBundle\SNS\Instagram\InstagramRequest;
use CoreBundle\SNS\Instagram\Response\PostResponse;
use MetzWeb\Instagram\Instagram;

class PostRequest extends InstagramRequest
{
    /**
     * @var Instagram
    */
    protected $client;

    /**
     * PostRequest constructor.
     *
     * @param Instagram $client
     */
    public function __construct(Instagram $client)
    {
        $this->client = $client;
    }

    /**
     * List Post
     *
     * @return PostResponse
     * @throws \Exception
     */
    public function listPost()
    {
        try {
            $response = $this->client->getUserMedia('self',500);
            return new PostResponse($response);
        } catch(\Exception $e) {
            throw new \Exception(
                sprintf("Instagram Driver With ErrorCode: %s and Message Code %s", $e->getCode(), $e->getMessage())
            );
        }
    }
}
