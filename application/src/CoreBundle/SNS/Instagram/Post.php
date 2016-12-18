<?php

namespace CoreBundle\SNS\Instagram;

use CoreBundle\SNS\AbstractPost;
use CoreBundle\SNS\Instagram\Request\PostRequest;
use CoreBundle\SNS\Instagram\Type\PostType;
use MetzWeb\Instagram\Instagram;

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
     * @param Instagram $client
     */
    public function __construct($accessToken, Instagram $client)
    {
        $this->initialize($accessToken, $client);
    }

    /**
     * Initialize the Instagram
     *
     * @param string $accessToken
     * @param Instagram $client
     */
    public function initialize($accessToken, Instagram $client)
    {
        $client->setAccessToken($accessToken);
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

            $hashTags = $this->getFullHashTags($item->tags, $item->caption->text);
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
       if($item->type != 'image') {
           return false;
       }

       if($this->getFullHashTags($item->tags, $item->caption->text))
        if(empty($item->tags)) {
            return false;
        }

        if(empty($item->link)) {
            return false;
        }

        if(empty($item->id)) {
            return false;
        }

        if(empty($item->created_time)) {
            return false;
        }

        return true;
    }

    public function getFullHashTags($hashTags, $message)
    {
        return array_unique(array_merge($hashTags, $this->getHashTags($message)));
    }
}