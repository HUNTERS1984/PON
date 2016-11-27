<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\UseList;
use CoreBundle\Manager\AppUserManager;
use CoreBundle\Manager\UseListManager;
use Faker\Factory;
use Symfony\Component\Console\Output\OutputInterface;

class UseCouponDummy extends BaseDummy implements IDummy
{
    /**@var AppUserManager */
    private $appUserManager;

    /** @var UseListManager $useListManager*/
    private $useListManager;

    /**
     * generate dummy data
     */
    public function generate(OutputInterface $output, $i = 0)
    {
        $faker = Factory::create();
        $appUserId = $faker->numberBetween(101,150);
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
        if($status == 2) {
            $useCoupon->setRequestedAt(new \DateTime());
        }
        $expiredTime = new \DateTime();
        $expiredTime->modify('+1 month');
        $useCoupon->setExpiredTime($expiredTime);
        $useCoupon = $this->useListManager->createUseList($useCoupon);
        return $useCoupon;
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

    /**
     * @param UseListManager $useListManager
     * @return UseCouponDummy
     */
    public function setUseListManager($useListManager)
    {
        $this->useListManager = $useListManager;
        return $this;
    }
}