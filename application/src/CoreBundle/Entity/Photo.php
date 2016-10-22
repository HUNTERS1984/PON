<?php

namespace CoreBundle\Entity;

/**
 * Photo
 */
class Photo
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $imageUrl;

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
    private $couponPhotos;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $storePhotos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->couponPhotos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->storePhotos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set imageUrl
     *
     * @param string $imageUrl
     *
     * @return Photo
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Photo
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
     * @return Photo
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
     * @return Photo
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
     * Add couponPhoto
     *
     * @param \CoreBundle\Entity\CouponPhoto $couponPhoto
     *
     * @return Photo
     */
    public function addCouponPhoto(\CoreBundle\Entity\CouponPhoto $couponPhoto)
    {
        $this->couponPhotos[] = $couponPhoto;

        return $this;
    }

    /**
     * Remove couponPhoto
     *
     * @param \CoreBundle\Entity\CouponPhoto $couponPhoto
     */
    public function removeCouponPhoto(\CoreBundle\Entity\CouponPhoto $couponPhoto)
    {
        $this->couponPhotos->removeElement($couponPhoto);
    }

    /**
     * Get couponPhotos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCouponPhotos()
    {
        return $this->couponPhotos;
    }

    /**
     * Add storePhoto
     *
     * @param \CoreBundle\Entity\StorePhoto $storePhoto
     *
     * @return Photo
     */
    public function addStorePhoto(\CoreBundle\Entity\StorePhoto $storePhoto)
    {
        $this->storePhotos[] = $storePhoto;

        return $this;
    }

    /**
     * Remove storePhoto
     *
     * @param \CoreBundle\Entity\StorePhoto $storePhoto
     */
    public function removeStorePhoto(\CoreBundle\Entity\StorePhoto $storePhoto)
    {
        $this->storePhotos->removeElement($storePhoto);
    }

    /**
     * Get storePhotos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getStorePhotos()
    {
        return $this->storePhotos;
    }
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $couponUserPhotos;


    /**
     * Add couponUserPhoto
     *
     * @param \CoreBundle\Entity\CouponUserPhoto $couponUserPhoto
     *
     * @return Photo
     */
    public function addCouponUserPhoto(\CoreBundle\Entity\CouponUserPhoto $couponUserPhoto)
    {
        $this->couponUserPhotos[] = $couponUserPhoto;

        return $this;
    }

    /**
     * Remove couponUserPhoto
     *
     * @param \CoreBundle\Entity\CouponUserPhoto $couponUserPhoto
     */
    public function removeCouponUserPhoto(\CoreBundle\Entity\CouponUserPhoto $couponUserPhoto)
    {
        $this->couponUserPhotos->removeElement($couponUserPhoto);
    }

    /**
     * Get couponUserPhotos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCouponUserPhotos()
    {
        return $this->couponUserPhotos;
    }
}
