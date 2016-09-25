<?php

namespace CoreBundle\Entity;

/**
 * CouponType
 */
class CouponType
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
    private $iconUrl;

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
    private $coupons;

    /**
     * Constructor
     */
    public function __construct()
    {
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
     * @return CouponType
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
     * Set iconUrl
     *
     * @param string $iconUrl
     *
     * @return CouponType
     */
    public function setIconUrl($iconUrl)
    {
        $this->iconUrl = $iconUrl;

        return $this;
    }

    /**
     * Get iconUrl
     *
     * @return string
     */
    public function getIconUrl()
    {
        return $this->iconUrl;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CouponType
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
     * @return CouponType
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
     * @return CouponType
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
     * Add coupon
     *
     * @param \CoreBundle\Entity\Coupon $coupon
     *
     * @return CouponType
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
}

