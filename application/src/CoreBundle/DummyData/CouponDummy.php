<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\Coupon;
use CoreBundle\Manager\StoreManager;
use Faker\Factory;

class CouponDummy extends BaseDummy implements IDummy
{
    /** @var StoreManager */
    private $storeManager;

    /**
     * generate dummy data
     */
    public function generate($i = 0)
    {
        $faker = Factory::create('ja_JP');
        $user = new Coupon();

        $storeId = ($i % 10) + 1;
        $storeData = $this->storeManager->findOneById($storeId);
        $expiredTime = new \DateTime();
        $expiredTime->modify('+1 month');

        $user
            ->setTitle($faker->name)
            ->setExpiredTime($expiredTime)
            ->setImageUrl($faker->imageUrl(640, 480, 'food'))
            ->setNeedLogin($faker->randomElement([false,true]))
            ->setCode($faker->ean13)
            ->setType($faker->numberBetween(1,2))
            ->setDescription($faker->paragraph(3))
            ->setStatus($faker->numberBetween(0,1))
            ->setSize($faker->numberBetween(1,100))
            ->setStore($storeData)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());
        $this->manager->dummy($user);
        return $user;
    }

    /**
     * @param mixed $storeManager
     * @return CouponDummy
     */
    public function setStoreManager($storeManager)
    {
        $this->storeManager = $storeManager;
        return $this;
    }
}