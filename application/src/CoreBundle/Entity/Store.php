<?php

namespace CoreBundle\Entity;

use JMS\Serializer\Annotation\Groups;

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
     * @var \CoreBundle\Entity\StoreType
     */
    private $storeType;

    /**
     * @var \CoreBundle\Entity\User
     */
    private $user;


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
     * Set storeType
     *
     * @param \CoreBundle\Entity\StoreType $storeType
     *
     * @return Store
     */
    public function setStoreType(\CoreBundle\Entity\StoreType $storeType = null)
    {
        $this->storeType = $storeType;

        return $this;
    }

    /**
     * Get storeType
     *
     * @return \CoreBundle\Entity\StoreType
     */
    public function getStoreType()
    {
        return $this->storeType;
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
    private $pushSettings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pushSettings = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $news;


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
     * @var \Doctrine\Common\Collections\Collection
     */
    private $couponTypes;


    /**
     * Add couponType
     *
     * @param \CoreBundle\Entity\CouponType $couponType
     *
     * @return Store
     */
    public function addCouponType(\CoreBundle\Entity\CouponType $couponType)
    {
        $this->couponTypes[] = $couponType;

        return $this;
    }

    /**
     * Remove couponType
     *
     * @param \CoreBundle\Entity\CouponType $couponType
     */
    public function removeCouponType(\CoreBundle\Entity\CouponType $couponType)
    {
        $this->couponTypes->removeElement($couponType);
    }

    /**
     * Get couponTypes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCouponTypes()
    {
        return $this->couponTypes;
    }
}
