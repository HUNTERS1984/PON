<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\CouponPhoto;
use CoreBundle\Entity\CouponUserPhoto;
use CoreBundle\Entity\Photo;
use CoreBundle\Manager\CouponPhotoManager;
use CoreBundle\Manager\CouponUserPhotoManager;
use CoreBundle\Manager\PhotoManager;
use CoreBundle\Manager\StoreManager;
use Faker\Factory;
use Ikwattro\FakerExtra\Provider\Hashtag;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

class CouponDummy extends BaseDummy implements IDummy
{
    /** @var StoreManager */
    private $storeManager;
    
    /** @var PhotoManager $photoManager */
    private $photoManager;

    /** @var CouponPhotoManager $couponPhotoManager */
    private $couponPhotoManager;

    /** @var CouponUserPhotoManager $couponUserPhotoManager */
    private $couponUserPhotoManager;

    /***
     * @var string $avatarDirPath
     */
    protected $avatarDirPath;

    /***
     * @var string $imageDirPath
     */
    protected $imageDirPath;

    /**
     * generate dummy data
     */
    public function generate(OutputInterface $output, $i = 0)
    {
        $faker = Factory::create('ja_JP');
        $faker->addProvider(new Hashtag($faker));

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

        $coupon = new Coupon();
        $coupon
            ->setTitle($faker->name)
            ->setExpiredTime($expiredTime)
            ->setImageUrl($this->manager->getImage($this->avatarDirPath))
            ->setNeedLogin($faker->randomElement([false,true]))
            ->setType($faker->numberBetween(1,2))
            ->setImpression(0)
            ->setDescription($description)
            ->setImpression(0)
            ->setHashTag($faker->hashtag(5, true))
            ->setStatus($faker->randomElement([true, false]))
            ->setSize($faker->numberBetween(1,100))
            ->setStore($storeData);
        $coupon = $this->manager->createCoupon($coupon);
        $output->writeln("");
        $progress = new ProgressBar($output, 2);
        $progress->setRedrawFrequency(1);
        $progress->start();
        $output->writeln("");
        $output->writeln("Begin Creating Photo...");
        for($i=0; $i< 2; $i++) {
            $photo1 = new Photo();
            $photo1
                ->setImageUrl($this->manager->getImage($this->imageDirPath))
                ->setPhotoId($this->photoManager->createID('PH'));
            $photo1 = $this->photoManager->createPhoto($photo1);


            $couponPhoto = new CouponPhoto();
            $couponPhoto
                ->setPhoto($photo1)
                ->setCoupon($coupon);

            $this->couponPhotoManager->save($couponPhoto, false);

            $photo2 = new Photo();
            $photo2
                ->setImageUrl($this->manager->getImage($this->imageDirPath))
                ->setPhotoId($this->photoManager->createID('PH'));
            $photo2 = $this->photoManager->createPhoto($photo2);

            $couponUserPhoto = new CouponUserPhoto();
            $couponUserPhoto
                ->setPhoto($photo2)
                ->setCoupon($coupon);

            $this->couponUserPhotoManager->save($couponUserPhoto, false);
            $progress->advance();
        }

        $progress->finish();
        $output->writeln("");
        $output->writeln("Finished Creating Photo...");
        $output->writeln("");
        $coupon = $this->manager->saveCoupon($coupon);
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

    /**
     * @param string $avatarDirPath
     * @return CouponDummy
     */
    public function setAvatarDirPath($avatarDirPath)
    {
        $this->avatarDirPath = $avatarDirPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getAvatarDirPath()
    {
        return $this->avatarDirPath;
    }

    /**
     * @param string $imageDirPath
     * @return CouponDummy
     */
    public function setImageDirPath($imageDirPath)
    {
        $this->imageDirPath = $imageDirPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getImageDirPath()
    {
        return $this->imageDirPath;
    }

    /**
     * @param CouponPhotoManager $couponPhotoManager
     * @return CouponDummy
     */
    public function setCouponPhotoManager(CouponPhotoManager $couponPhotoManager): CouponDummy
    {
        $this->couponPhotoManager = $couponPhotoManager;
        return $this;
    }

    /**
     * @param CouponUserPhotoManager $couponUserPhotoManager
     * @return CouponDummy
     */
    public function setCouponUserPhotoManager(CouponUserPhotoManager $couponUserPhotoManager): CouponDummy
    {
        $this->couponUserPhotoManager = $couponUserPhotoManager;
        return $this;
    }
}