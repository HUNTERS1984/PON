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
     * @var integer
     */
    private $socialUserId;

    /**
     * @var string
     */
    private $SocialMediaId;

    /**
     * @var string
     */
    private $caption;

    /**
     * @var string
     */
    private $description;

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
    private $socialUserName;

    /**
     * @var string
     */
    private $socialAvatar;

    /**
     * @var string
     */
    private $imageUrl;

    /**
     * @var string
     */
    private $url;

    /**
     * @var \CoreBundle\Entity\AppUser
     */
    private $appUser;


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
     * Set socialUserId
     *
     * @param integer $socialUserId
     *
     * @return Post
     */
    public function setSocialUserId($socialUserId)
    {
        $this->socialUserId = $socialUserId;

        return $this;
    }

    /**
     * Get socialUserId
     *
     * @return integer
     */
    public function getSocialUserId()
    {
        return $this->socialUserId;
    }

    /**
     * Set socialMediaId
     *
     * @param string $socialMediaId
     *
     * @return Post
     */
    public function setSocialMediaId($socialMediaId)
    {
        $this->SocialMediaId = $socialMediaId;

        return $this;
    }

    /**
     * Get socialMediaId
     *
     * @return string
     */
    public function getSocialMediaId()
    {
        return $this->SocialMediaId;
    }

    /**
     * Set caption
     *
     * @param string $caption
     *
     * @return Post
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Post
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * Set socialUserName
     *
     * @param string $socialUserName
     *
     * @return Post
     */
    public function setSocialUserName($socialUserName)
    {
        $this->socialUserName = $socialUserName;

        return $this;
    }

    /**
     * Get socialUserName
     *
     * @return string
     */
    public function getSocialUserName()
    {
        return $this->socialUserName;
    }

    /**
     * Set socialAvatar
     *
     * @param string $socialAvatar
     *
     * @return Post
     */
    public function setSocialAvatar($socialAvatar)
    {
        $this->socialAvatar = $socialAvatar;

        return $this;
    }

    /**
     * Get socialAvatar
     *
     * @return string
     */
    public function getSocialAvatar()
    {
        return $this->socialAvatar;
    }

    /**
     * Set imageUrl
     *
     * @param string $imageUrl
     *
     * @return Post
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * Get imageUrl
     *
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add tag
     *
     * @param \CoreBundle\Entity\Tag $tag
     *
     * @return Post
     */
    public function addTag(\CoreBundle\Entity\Tag $tag)
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Remove tag
     *
     * @param \CoreBundle\Entity\Tag $tag
     */
    public function removeTag(\CoreBundle\Entity\Tag $tag)
    {
        $this->tags->removeElement($tag);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }
}
