<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\UseList;
use CoreBundle\Manager\AppUserManager;
use Faker\Factory;

class UseCouponDummy extends BaseDummy implements IDummy
{
    /**@var AppUserManager */
    private $appUserManager;

    /**
     * generate dummy data
     */
    public function generate($i = 0, $j =0)
    {
        $faker = Factory::create();
        $appUserId = $faker->numberBetween(51,100);
        $couponId = $faker->numberBetween(1,50);
        $status = random_int(0,4);
        $appUser = $this->appUserManager->findOneById($appUserId);
        /**@var Coupon $coupon */
        $coupon = $this->manager->findOneById($couponId);
        $useCoupon = new UseList();
        $useCoupon->setAppUser($appUser);
        $useCoupon->setCoupon($coupon);
        $useCoupon->setCode($faker->ean13);
        $useCoupon->setStatus($status);
        $useCoupon->setCreatedAt(new \DateTime());
        $useCoupon->setUpdatedAt(new \DateTime());
        if($status == 2) {
            $useCoupon->setRequestedAt(new \DateTime());
        }
        $expiredTime = new \DateTime();
        $expiredTime->modify('+1 month');
        $useCoupon->setExpiredTime($expiredTime);
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