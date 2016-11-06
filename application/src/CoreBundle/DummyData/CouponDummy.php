<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\CouponPhoto;
use CoreBundle\Entity\CouponUserPhoto;
use CoreBundle\Entity\Photo;
use CoreBundle\Manager\PhotoManager;
use CoreBundle\Manager\StoreManager;
use Faker\Factory;

class CouponDummy extends BaseDummy implements IDummy
{
    /** @var StoreManager */
    private $storeManager;
    
    /** @var PhotoManager $photoManager */
    private $photoManager;

    /**
     * generate dummy data
     */
    public function generate($i = 0)
    {
        $faker = Factory::create('ja_JP');
        $coupon = new Coupon();

        $storeId = $faker->numberBetween(1,10);
        $storeData = $this->storeManager->findOneById($storeId);
        $expiredTime = new \DateTime();
        $expiredTime->modify('+1 month');


        $description = '説明が入ります説明が入ります説明が入ります説明が入り
ます説明が入ります説明が入ります説明が入ります説明が
入ります説明が入ります説明が入ります説明が入ります説
明が入ります説明が入ります説明が入ります説明が入りま
す説明が入ります..説明が入ります説明が入ります説明が
入ります説明が入ります説明が入ります説明が入ります説
明が入ります説明が入ります..説明が入ります説明が入り
ます説明が入ります説明が入ります説明が入ります説明が
入ります説明が入ります説明が入ります';

        $coupon
            ->setTitle($faker->name)
            ->setExpiredTime($expiredTime)
            ->setImageUrl($faker->imageUrl(640, 480, 'food'))
            ->setNeedLogin($faker->randomElement([false,true]))
            ->setType($faker->numberBetween(1,2))
            ->setImpression(0)
            ->setDescription($description)
            ->setImpression(0)
            ->setStatus($faker->randomElement([true, false]))
            ->setSize($faker->numberBetween(1,100))
            ->setStore($storeData);

        for($i=0; $i< 5; $i++) {
            $photo = new Photo();
            $photo
                ->setImageUrl($faker->imageUrl(640, 480, 'food'));
            $photo = $this->photoManager->createPhoto($photo);

            $couponPhoto = new CouponPhoto();
            $couponPhoto
                ->setPhoto($photo)
                ->setCoupon($coupon);

            $couponUserPhoto = new CouponUserPhoto();
            $couponUserPhoto
                ->setPhoto($photo)
                ->setCoupon($coupon);

            $coupon->addCouponPhoto($couponPhoto);
            $coupon->addCouponUserPhoto($couponUserPhoto);
        }

        $coupon = $this->manager->createCoupon($coupon);
        return $coupon;
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

    /**
     * @param PhotoManager $photoManager
     * @return CouponDummy
     */
    public function setPhotoManager($photoManager)
    {
        $this->photoManager = $photoManager;
        return $this;
    }
}