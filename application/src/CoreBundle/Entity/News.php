<?php

namespace CoreBundle\Entity;

/**
 * News
 */
class News
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $imageUrl;

    /**
     * @var string
     */
    private $introduction;

    /**
     * @var string
     */
    private $description;

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
     * @var \CoreBundle\Entity\Store
     */
    private $store;

    /**
     * @var \CoreBundle\Entity\NewsCategory
     */
    private $newsCategory;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $newsPhoto;

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
     * Set title
     *
     * @param string $title
     *
     * @return News
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set imageUrl
     *
     * @param string $imageUrl
     *
     * @return News
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
     * Set introduction
     *
     * @param string $introduction
     *
     * @return News
     */
    public function setIntroduction($introduction)
    {
        $this->introduction = $introduction;

        return $this;
    }

    /**
     * Get introduction
     *
     * @return string
     */
    public function getIntroduction()
    {
        return $this->introduction;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return News
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return News
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
     * @return News
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
     * @return News
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
     * Set newsCategory
     *
     * @param \CoreBundle\Entity\NewsCategory $newsCategory
     *
     * @return News
     */
    public function setNewsCategory(\CoreBundle\Entity\NewsCategory $newsCategory = null)
    {
        $this->newsCategory = $newsCategory;

        return $this;
    }

    /**
     * Get newsCategory
     *
     * @return \CoreBundle\Entity\NewsCategory
     */
    public function getNewsCategory()
    {
        return $this->newsCategory;
    }

    /**
     * Set store
     *
     * @param \CoreBundle\Entity\Store $store
     *
     * @return News
     */
    public function setStore(\CoreBundle\Entity\Store $store = null)
    {
        $this->store = $store;

        return $this;
    }

    /**
     * Get store
     *
     * @return \CoreBundle\Entity\Store
     */
    public function getStore()
    {
        return $this->store;
    }

    /**
     * Add newsPhoto
     *
     * @param \CoreBundle\Entity\NewsPhoto $newsPhoto
     *
     * @return News
     */
    public function addNewsPhoto(\CoreBundle\Entity\NewsPhoto $newsPhoto)
    {
        $this->newsPhoto[] = $newsPhoto;

        return $this;
    }

    /**
     * Remove newsPhoto
     *
     * @param \CoreBundle\Entity\NewsPhoto $newsPhoto
     */
    public function removeNewsPhoto(\CoreBundle\Entity\NewsPhoto $newsPhoto)
    {
        $this->newsPhoto->removeElement($newsPhoto);
    }

    /**
     * Get newsPhotos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNewsPhotos()
    {
        return $this->newsPhoto;
    }

    /**
     * @var string
     */
    private $newsId;


    /**
     * Set newsId
     *
     * @param string $newsId
     *
     * @return News
     */
    public function setNewsId($newsId)
    {
        $this->newsId = $newsId;

        return $this;
    }

    /**
     * Get newsId
     *
     * @return string
     */
    public function getNewsId()
    {
        return $this->newsId;
    }
}
