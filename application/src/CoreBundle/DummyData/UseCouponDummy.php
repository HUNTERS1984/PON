<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\UseList;
use CoreBundle\Manager\AppUserManager;

class UseCouponDummy extends BaseDummy implements IDummy
{
    /**@var AppUserManager */
    private $appUserManager;

    /**
     * generate dummy data
     */
    public function generate($i = 0, $j =0)
    {
        $appUserId = ($i % 10) + 1;
        $couponId = ($j % 10) + 1;
        $appUser = $this->appUserManager->findOneById($appUserId);
        /**@var Coupon $coupon */
        $coupon = $this->manager->findOneById($couponId);
        $useCoupon = new UseList();
        $useCoupon->setAppUser($appUser);
        $useCoupon->setCoupon($coupon);
        $useCoupon->setStatus(0);
        $coupon->addUseList($useCoupon);
        $this->manager->saveCoupon($coupon);
        return $coupon;
    }

    /**
     * @param AppUserManager $appUserManager
     * @return UseCouponDummy
     */
    public function setAppUserManager($appUserManager)
    {
        $this->appUserManager = $appUserManager;
        return $this;
    }
}