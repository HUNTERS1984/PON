<?php

namespace CoreBundle\SNS\Facebook\Request;

use CoreBundle\SNS\Facebook\FacebookRequest;
use CoreBundle\SNS\Facebook\Response\PostResponse;
use Facebook\Facebook;
use Facebook\GraphNodes\GraphEdge;

class PostRequest extends FacebookRequest
{
    /**
     * @var Facebook
    */
    protected $client;

    /**
     * PostRequest constructor.
     *
     * @param Facebook $client
     */
    public function __construct(Facebook $client)
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
            $response = $this->client->get('me/Posts?fields=id,message,story,created_time,type,full_picture,is_published,attachments,privacy,permalink_url&limit=500');
            return new PostResponse($response);
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            throw new \Exception(sprintf("Facebook Driver: %s", $e->getMessage()));
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            throw new \Exception(sprintf("Facebook Driver: %s", $e->getMessage()));
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
        } catch(\Facebook\Exceptions\FacebookResponseException $e) {
            throw new \Exception(sprintf("Facebook Driver: %s", $e->getMessage()));
        } catch(\Facebook\Exceptions\FacebookSDKException $e) {
            throw new \Exception(sprintf("Facebook Driver: %s", $e->getMessage()));
        }
    }
}
