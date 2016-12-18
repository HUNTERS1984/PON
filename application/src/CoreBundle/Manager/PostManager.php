<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\Post;
use CoreBundle\Paginator\Pagination;
use Elastica\Filter\Missing;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class PostManager extends AbstractManager
{
    /**
     * @var Pagination
    */
    protected $pagination;

    /**
     * @var TransformedFinder
    */
    protected $postFinder;

    /**
     * @var Pagination $pagination
    */
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * @param Post $post
     *
     * @return Post
     */
    public function createPost(Post $post)
    {
        if(!$post->getPostId()) {
            $post->setPostId($this->createID('PO'));
        }

        $post->setCreatedAt(new \DateTime());
        return $this->savePost($post);
    }

    /**
     * @param Post $post
     *
     * @return Post
     */
    public function savePost(Post $post)
    {
        $post->setUpdatedAt(new \DateTime());
        return $this->save($post);
    }

    /**
     * @param Post $post
     *
     * @return boolean
     */
    public function deletePost(Post $post)
    {
        $post
            ->setDeletedAt(new \DateTime());
        return $this->savePost($post);
    }

    /**
     * get Post
     *
     * @param $id
     * @return null | Post
     */
    public function getPost($id)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Query\Term(['id' => ['value' => $id]]));
        $result = $this->postFinder->find($query);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * get Post
     *
     * @param $snsId
     * @return null | Post
     */
    public function getPostBySNSId($snsId)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Query\Term(['snsId' => ['value' => $snsId]]));
        $result = $this->postFinder->find($query);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * List Post
     *
     * @return array
     */
    public function listPost()
    {
        $query = new Query();
        $query
            ->setPostFilter(new Missing('deletedAt'));
        $mainQuery = new Query\BoolQuery();
        $mainQuery
            ->addMust(new Query\Term(['status' => ['value' => 0]]));
        $query->setQuery($mainQuery);

        $pagination = $this->postFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults(0, 500);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return ['data' => $results, 'total' => $total];
    }

    /**
     * @param TransformedFinder $postFinder
     * @return PostManager
     */
    public function setPostFinder($postFinder)
    {
        $this->postFinder = $postFinder;
        return $this;
    }

}