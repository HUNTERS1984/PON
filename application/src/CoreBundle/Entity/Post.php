<?php

namespace CoreBundle\Entity;

/**
 * Post
 */
class Post
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $postId;

    /**
     * @var \DateTime
     */
    private $createdTime;

    /**
     * @var integer
     */
    private $status;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var \DateTime
     */
    private $deletedAt;

    /**
     * @var string
     */
    private $url;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $postPhotos;

    /**
     * @var \CoreBundle\Entity\AppUser
     */
    private $appUser;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->postPhotos = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set message
     *
     * @param string $message
     *
     * @return Post
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set postId
     *
     * @param string $postId
     *
     * @return Post
     */
    public function setPostId($postId)
    {
        $this->postId = $postId;

        return $this;
    }

    /**
     * Get postId
     *
     * @return string
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * Set createdTime
     *
     * @param \DateTime $createdTime
     *
     * @return Post
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;

        return $this;
    }

    /**
     * Get createdTime
     *
     * @return \DateTime
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Post
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Post
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Post
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set deletedAt
     *
     * @param \DateTime $deletedAt
     *
     * @return Post
     */
    public function setDeletedAt($deletedAt)
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * Get deletedAt
     *
     * @return \DateTime
     */
    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Post
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Add postPhoto
     *
     * @param \CoreBundle\Entity\PostPhoto $postPhoto
     *
     * @return Post
     */
    public function addPostPhoto(\CoreBundle\Entity\PostPhoto $postPhoto)
    {
        $this->postPhotos[] = $postPhoto;

        return $this;
    }

    /**
     * Remove postPhoto
     *
     * @param \CoreBundle\Entity\PostPhoto $postPhoto
     */
    public function removePostPhoto(\CoreBundle\Entity\PostPhoto $postPhoto)
    {
        $this->postPhotos->removeElement($postPhoto);
    }

    /**
     * Get postPhotos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPostPhotos()
    {
        return $this->postPhotos;
    }

    /**
     * Set appUser
     *
     * @param \CoreBundle\Entity\AppUser $appUser
     *
     * @return Post
     */
    public function setAppUser(\CoreBundle\Entity\AppUser $appUser = null)
    {
        $this->appUser = $appUser;

        return $this;
    }

    /**
     * Get appUser
     *
     * @return \CoreBundle\Entity\AppUser
     */
    public function getAppUser()
    {
        return $this->appUser;
    }
    /**
     * @var string
     */
    private $snsId;


    /**
     * Set snsId
     *
     * @param string $snsId
     *
     * @return Post
     */
    public function setSnsId($snsId)
    {
        $this->snsId = $snsId;

        return $this;
    }

    /**
     * Get snsId
     *
     * @return string
     */
    public function getSnsId()
    {
        return $this->snsId;
    }
}
