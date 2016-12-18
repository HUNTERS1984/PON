<?php

namespace CoreBundle\SNS\Twitter\Request;

use Abraham\TwitterOAuth\TwitterOAuth;
use CoreBundle\SNS\Twitter\Response\PostResponse;
use CoreBundle\SNS\Twitter\TwitterRequest;
use Facebook\GraphNodes\GraphEdge;

class PostRequest extends TwitterRequest
{
    /**
     * @var TwitterOAuth
    */
    protected $client;

    /**
     * PostRequest constructor.
     *
     * @param TwitterOAuth $client
     */
    public function __construct(TwitterOAuth $client)
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
            $response = $this->client->get("statuses/user_timeline");
            return new PostResponse($response);
        } catch(\Exception $e) {
            throw new \Exception(sprintf("Twitter Driver With ErrorCode: %s and Message Code %s", $e->getCode(), $e->getMessage()));
        }
    }

    /**
     * get Next Page
     *
     * @param GraphEdge $graphEdge
     *
     * @return PostResponse
     * @throws \Exception
    */
    public function getNextPage(GraphEdge $graphEdge)
    {
        try {
            $this->client->next($graphEdge);
            return new PostResponse($this->client->getLastResponse());
        } catch(\Exception $e) {
            throw new \Exception(sprintf("Twitter Driver: %s", $e->getMessage()));
        }
    }
}
