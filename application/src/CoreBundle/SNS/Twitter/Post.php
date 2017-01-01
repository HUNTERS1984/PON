<?php

namespace CoreBundle\SNS\Twitter;


use Abraham\TwitterOAuth\TwitterOAuth;
use CoreBundle\SNS\AbstractPost;
use CoreBundle\SNS\Twitter\Request\PostRequest;
use CoreBundle\SNS\Twitter\Type\PostType;

class Post extends AbstractPost
{
    /**
     * @var PostRequest
     */
    protected $request;

    /**
     * Construct
     *
     * @param string $accessToken
     * @param string $tokenSecret
     * @param TwitterOAuth $client
     */
    public function __construct($accessToken, $tokenSecret, TwitterOAuth $client)
    {
        $this->initialize($accessToken, $tokenSecret, $client);
    }

    /**
     * Initialize the Twitter
     *
     * @param string $accessToken
     * @param string $tokenSecret
     * @param TwitterOAuth $client
     */
    public function initialize($accessToken, $tokenSecret, TwitterOAuth $client)
    {
        $client->setOauthToken($accessToken, $tokenSecret);
        $this->request = new PostRequest($client);
    }

    /**
     * {@inheritdoc}
     */
    public function listPost(\DateTime $from, \DateTime $to)
    {
        $response = $this->request->listPost();
        $result = $response->getResult();
        $posts = $result;
        $results = [];
        foreach($posts as $item) {
            if(!$this->validatePost($item)) {
                continue;
            }

            $hashTags = $this->getHashTags($item->entities->hashtags);

            $post = new PostType($item, $this->getPrefix());
            $post
                ->setHashTags($hashTags);
            $createdAt = $post->getCreatedAt();

            if($createdAt < $from || $createdAt > $to) {
                return $results;
            }
            $results[] = $post;
        }

        return $results;
    }

    public function validatePost($item)
    {
       $entity = $item->entities;

        if(empty($entity->hashtags)) {
            return false;
        }

//        if(empty($entity->media)) {
//            return false;
//        }

        if(empty($item->id)) {
            return false;
        }

        if(empty($item->created_at)) {
            return false;
        }

        return true;
    }

    public function getHashTags($hashTags)
    {
        $tags = array_map(function($hashTag) {
            return $hashTag->text;
        }, $hashTags);

        return array_unique($tags);
    }
}