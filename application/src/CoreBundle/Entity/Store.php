<?php

namespace CoreBundle\Entity;

/**
 * Store
 */
class Store
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $latitude;

    /**
     * @var string
     */
    private $longtitude;

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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $pushSettings;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $news;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $coupons;

    /**
     * @var \CoreBundle\Entity\User
     */
    private $user;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pushSettings = new \Doctrine\Common\Collections\ArrayCollection();
        $this->news = new \Doctrine\Common\Collections\ArrayCollection();
        $this->coupons = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Store
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return Store
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longtitude
     *
     * @param string $longtitude
     *
     * @return Store
     */
    public function setLongtitude($longtitude)
    {
        $this->longtitude = $longtitude;

        return $this;
    }

    /**
     * Get longtitude
     *
     * @return string
     */
    public function getLongtitude()
    {
        return $this->longtitude;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Store
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
     * @return Store
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
     * @return Store
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
     * Add pushSetting
     *
     * @param \CoreBundle\Entity\PushSetting $pushSetting
     *
     * @return Store
     */
    public function addPushSetting(\CoreBundle\Entity\PushSetting $pushSetting)
    {
        $this->pushSettings[] = $pushSetting;

        return $this;
    }

    /**
     * Remove pushSetting
     *
     * @param \CoreBundle\Entity\PushSetting $pushSetting
     */
    public function removePushSetting(\CoreBundle\Entity\PushSetting $pushSetting)
    {
        $this->pushSettings->removeElement($pushSetting);
    }

    /**
     * Get pushSettings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPushSettings()
    {
        return $this->pushSettings;
    }

    /**
     * Add news
     *
     * @param \CoreBundle\Entity\News $news
     *
     * @return Store
     */
    public function addNews(\CoreBundle\Entity\News $news)
    {
        $this->news[] = $news;

        return $this;
    }

    /**
     * Remove news
     *
     * @param \CoreBundle\Entity\News $news
     */
    public function removeNews(\CoreBundle\Entity\News $news)
    {
        $this->news->removeElement($news);
    }

    /**
     * Get news
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Add coupon
     *
     * @param \CoreBundle\Entity\Coupon $coupon
     *
     * @return Store
     */
    public function addCoupon(\CoreBundle\Entity\Coupon $coupon)
    {
        $this->coupons[] = $coupon;

        return $this;
    }

    /**
     * Remove coupon
     *
     * @param \CoreBundle\Entity\Coupon $coupon
     */
    public function removeCoupon(\CoreBundle\Entity\Coupon $coupon)
    {
        $this->coupons->removeElement($coupon);
    }

    /**
     * Get coupons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCoupons()
    {
        return $this->coupons;
    }

    /**
     * Set user
     *
     * @param \CoreBundle\Entity\User $user
     *
     * @return Store
     */
    public function setUser(\CoreBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \CoreBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $followLists;


    /**
     * Add followList
     *
     * @param \CoreBundle\Entity\FollowList $followList
     *
     * @return Store
     */
    public function addFollowList(\CoreBundle\Entity\FollowList $followList)
    {
        $this->followLists[] = $followList;

        return $this;
    }

    /**
     * Remove followList
     *
     * @param \CoreBundle\Entity\FollowList $followList
     */
    public function removeFollowList(\CoreBundle\Entity\FollowList $followList)
    {
        $this->followLists->removeElement($followList);
    }

    /**
     * Get followLists
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFollowLists()
    {
        return $this->followLists;
    }
    /**
     * @var \CoreBundle\Entity\Category
     */
    private $category;


    /**
     * Set category
     *
     * @param \CoreBundle\Entity\Category $category
     *
     * @return Store
     */
    public function setCategory(\CoreBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \CoreBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }
}
