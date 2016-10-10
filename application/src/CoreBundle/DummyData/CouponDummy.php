<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\Coupon;
use Faker\Factory;

class CouponDummy extends BaseDummy implements IDummy
{
    /**
     * generate dummy data
     */
    public function generate($i = 0)
    {
        $faker = Factory::create();
        $user = new Coupon();
        $email = 'coupon_'.$i;

        $storeId = ($i % 10) + 1;
        $storeData = $this->storeManager->findOneById($storeId);


        $user

            ->setTitle($email)
            ->setDescription("")
            ->setStartDate(new \DateTime())
            ->setEndDate(new \DateTime())
            ->setStatus(1)
            ->setType(1)
            ->setImageUrl($faker->imageUrl(640, 480, 'food'))
            ->setSize(1)
            ->setStore($storeData)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());
        $this->manager->dummy($user);
        return $user;
    }
}