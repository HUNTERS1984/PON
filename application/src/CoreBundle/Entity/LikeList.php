<?php

namespace CoreBundle\Entity;

/**
 * LikeList
 */
class LikeList
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set coupon
     *
     * @param \CoreBundle\Entity\Coupon $coupon
     *
     * @return LikeList
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
     * @return LikeList
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
 

}
