<?php

namespace CoreBundle\SNS\Facebook;


use CoreBundle\SNS\AbstractPost;
use CoreBundle\SNS\Facebook\Response\PostResponse;
use CoreBundle\SNS\Facebook\Request\PostRequest;
use CoreBundle\SNS\Facebook\Type\PostType;
use CoreBundle\SNS\Type\BasePostType;
use Facebook\Facebook;

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
     * @param Facebook $client
     */
    public function __construct($accessToken, Facebook $client)
    {
        $this->initialize($accessToken, $client);
    }

    /**
     * Initialize the Facebook
     *
     * @param string $accessToken
     * @param Facebook $client
     */
    public function initialize($accessToken, Facebook $client)
    {
        $client->setDefaultAccessToken($accessToken);
        $this->request = new PostRequest($client);
    }

    /**
     * {@inheritdoc}
     */
    public function listPost(\DateTime $from, \DateTime $to)
    {
        $response = $this->request->listPost();
        $result = $response->getResult();
        $posts = $result['data'];
        $results = [];
        while (true) {
            foreach($posts as $item) {
                if($item['type'] !== 'photo') {
                    continue;
                }

                if(!$this->validatePost($item)) {
                    continue;
                }

                $item['message'] = !empty($item['message']) ? $item['message'] : $item['story'];

                if(empty($hashTags = $this->getHashTags($item['message']))) {
                    continue;
                }
                $post = new PostType($item, $this->getPrefix());
                $post->setHashTags($hashTags);
                $createdAt = $post->getCreatedAt();

                if($post->getPrivacy() !== 'EVERYONE') {

                }

                if($createdAt < $from || $createdAt > $to) {
                    return $results;
                }
                $results[] = $post;
            }
            if(empty($posts)){
                break;
            }
            $response = $this->request->getNextPage($response->getGraphEdge());
            $result = $response->getResult();
            $posts = $result['data'];
        }

        return $results;
    }

    public function validatePost($item)
    {
        if(empty($item['id'])) {
            return false;
        }

        if(empty($item['created_time'])) {
            return false;
        }

        if(empty($item['is_published'])) {
            return false;
        }

        if(!isset($item['privacy']['value'])) {
            return false;
        }

        if(!isset($item['message']) && !isset($item['story'])) {
            return false;
        }

        if(!isset($item['permalink_url'])) {
            return false;
        }

        return true;
    }

    public function getPosts()
    {
        /** @var PostResponse $response */
        $response = $this->request->listPost();
        $result = $response->getResult();
        return $result;
    }
}