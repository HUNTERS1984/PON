<?php

namespace CoreBundle\Entity;

/**
 * PostPhoto
 */
class PostPhoto
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \CoreBundle\Entity\Post
     */
    private $post;

    /**
     * @var \CoreBundle\Entity\Photo
     */
    private $photo;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set post
     *
     * @param \CoreBundle\Entity\Post $post
     *
     * @return PostPhoto
     */
    public function setPost(\CoreBundle\Entity\Post $post = null)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get post
     *
     * @return \CoreBundle\Entity\Post
     */
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set photo
     *
     * @param \CoreBundle\Entity\Photo $photo
     *
     * @return PostPhoto
     */
    public function setPhoto(\CoreBundle\Entity\Photo $photo = null)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \CoreBundle\Entity\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}
