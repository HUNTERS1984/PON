<?php

namespace CoreBundle\Event;

use CoreBundle\Entity\Coupon;
use Symfony\Component\EventDispatcher\Event;

class CouponEvents extends Event
{

    const PRE_CREATE = 'pon.event.coupon.pre_create';

    const POST_CREATE = 'pon.event.coupon.post_create';

    /**
     * @var Coupon
     */
    protected $coupon;

    /**
     * @param Coupon $coupon
     * @return CouponEvents
     */
    public function setCoupon(Coupon $coupon): CouponEvents
    {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * @return Coupon
     */
    public function getCoupon(): Coupon
    {
        return $this->coupon;
    }
}
