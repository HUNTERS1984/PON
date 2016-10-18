<?php

namespace CoreBundle\Entity;

/**
 * CouponUserPhoto
 */
class CouponUserPhoto
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
     * Set coupon
     *
     * @param \CoreBundle\Entity\Coupon $coupon
     *
     * @return CouponUserPhoto
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
     * Set photo
     *
     * @param \CoreBundle\Entity\Photo $photo
     *
     * @return CouponUserPhoto
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
