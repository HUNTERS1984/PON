<?php

namespace CoreBundle\Queue\Proxy;

use CoreBundle\Manager\PhotoManager;
use CoreBundle\Manager\PostManager;
use CoreBundle\Manager\PostPhotoManager;

class ScrappingProxy
{
    /**
     * @var PostManager
    */
    private $postManager;

    /**
     * @var PostPhotoManager
    */
    private $postPhotoManager;

    /**
     * @var PhotoManager
    */
    private $photoManager;

    /**
     * @param PostManager $postManager
     * @return ScrappingProxy
     */
    public function setPostManager(PostManager $postManager): ScrappingProxy
    {
        $this->postManager = $postManager;
        return $this;
    }

    /**
     * @return PostManager
     */
    public function getPostManager(): PostManager
    {
        return $this->postManager;
    }

    /**
     * @param PostPhotoManager $postPhotoManager
     * @return ScrappingProxy
     */
    public function setPostPhotoManager(PostPhotoManager $postPhotoManager): ScrappingProxy
    {
        $this->postPhotoManager = $postPhotoManager;
        return $this;
    }

    /**
     * @return PostPhotoManager
     */
    public function getPostPhotoManager(): PostPhotoManager
    {
        return $this->postPhotoManager;
    }

    /**
     * @param PhotoManager $photoManager
     * @return ScrappingProxy
     */
    public function setPhotoManager($photoManager)
    {
        $this->photoManager = $photoManager;
        return $this;
    }

    /**
     * @return PhotoManager
     */
    public function getPhotoManager()
    {
        return $this->photoManager;
    }
}