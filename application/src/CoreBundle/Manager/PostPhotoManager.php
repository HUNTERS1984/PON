<?php

namespace CoreBundle\Manager;


use CoreBundle\Entity\Post;
use CoreBundle\Entity\PostPhoto;
use Elastica\Query\Term;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class PostPhotoManager extends AbstractManager
{
    /**
     * @var TransformedFinder $couponFinder
     */
    protected $postPhotoFinder;

    /**
     * @param TransformedFinder $postPhotoFinder
     * @return PostPhotoManager
     */
    public function setPostPhotoFinder(TransformedFinder $postPhotoFinder)
    {
        $this->postPhotoFinder = $postPhotoFinder;

        return $this;
    }

    /**
     * @return TransformedFinder
     */
    public function getPostPhotoFinder()
    {
        return $this->postPhotoFinder;
    }

    /**
     * get PostPhoto
     *
     * @param integer $id
     * @return PostPhoto|null
     */
    public function getPostPhoto($id)
    {
        $result = $this->postPhotoFinder->find(new Term(['id' => $id]));

        return $result;
    }

    /**
     *  getPostPhotosByPost
     *
     * @param Post $post
     * @return PostPhoto|null
     */
    public function getPostPhotosByPost(Post $post)
    {
        $result = $this->postPhotoFinder->find(new Term(['post.id' => $post->getId()]), 500);

        return $result;
    }
}