<?php

namespace CoreBundle\Entity;

/**
 * UseList
 */
class UseList
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \CoreBundle\Entity\Coupon
     */
    private $coupon;

    /**
     * @var \CoreBundle\Entity\AppUser
     */
    private $appUser;

    /**
     * @var \CoreBundle\Entity\Store
     */
    private $store;

    /**
     * @var \CoreBundle\Entity\Category
     */
    private $category;


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
    private $usedAt;



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
     * Set usedAt
     *
     * @param \DateTime $usedAt
     *
     * @return Store
     */
    public function setUsedAt($usedAt)
    {
        $this->usedAt = $usedAt;

        return $this;
    }

    /**
     * Get usedAt
     *
     * @return \DateTime
     */
    public function getUsedAt()
    {
        return $this->usedAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Coupon
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get num used
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }




    /**
     * Set coupon
     *
     * @param \CoreBundle\Entity\Coupon $coupon
     *
     * @return UseList
     */
    public function setCoupon(\CoreBundle\Entity\Coupon $coupon = null)
    {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * Get coupon
     *
     * @return \CoreBundle\Entity\Coupon
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * Set appUser
     *
     * @param \CoreBundle\Entity\AppUser $appUser
     *
     * @return UseList
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
     * Set store
     *
     * @param \CoreBundle\Entity\Store $store
     *
     * @return UseList
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
