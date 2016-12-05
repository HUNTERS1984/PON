<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Photo;
use CoreBundle\Entity\Store;
use CoreBundle\Entity\StorePhoto;
use CoreBundle\Manager\CategoryManager;
use CoreBundle\Manager\AppUserManager;
use CoreBundle\Manager\PhotoManager;
use CoreBundle\Manager\StorePhotoManager;
use CoreBundle\Manager\UserManager;
use Faker\Factory;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class StoreDummy extends BaseDummy implements IDummy
{
    /** @var CategoryManager $categoryManager */
    private $categoryManager;

    /** @var PhotoManager $photoManager */
    private $photoManager;

    /** @var StorePhotoManager $storePhotoManager */
    private $storePhotoManager;

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
        $store = new Store();
        $name = $faker->name;
        $categoryId = $faker->numberBetween(1, 5);
        $category = $this->categoryManager->findOneById($categoryId);

        $file = new Filesystem();
        if(!$file->exists($this->avatarDirPath)) {
            $file->mkdir($this->avatarDirPath);
        }

        if(!$file->exists($this->imageDirPath)) {
            $file->mkdir($this->imageDirPath);
        }
        
        $arrayGeo = [
            [10.785871, 106.6851],
            [21.030355, 105.832855],
            [10.784338, 106.684574],
            [10.788322, 106.685196],
            [10.786783, 106.683758],
            [10.785076, 106.682965],
            [10.785919, 106.686226],
            [21.037016 , 105.832961],
            [10.839665, 106.779503],
            [10.839812, 106.780339],
            [10.840795, 106.778982],
            [10.840592, 106.777550],
            [21.032588, 105.833231]
        ];
        $location = $faker->numberBetween(0,12);
        $lat = $arrayGeo[$location][0];
        $long = $arrayGeo[$location][1];
        $startTime = new \DateTime('2000-12-31 08:00');
        $endTime = new \DateTime('2000-12-31 23:00');
        $store
            ->setTitle($name)
            ->setOperationStartTime($startTime)
            ->setOperationEndTime($endTime)
            ->setAvatarUrl($this->manager->getImage($this->avatarDirPath))
            ->setTel($faker->phoneNumber)
            ->setLatitude($lat)
            ->setLongitude($long)
            ->setAddress($faker->address)
            ->setCloseDate("土曜日と日曜日")
            ->setAveBill($faker->numberBetween(1,100))
            ->setHelpText($faker->paragraph(3))
            ->setCategory($category);

        /** @var Store $store*/
        $store = $this->manager->createStore($store);

        $output->writeln("");
        $progress = new ProgressBar($output, 2);
        $progress->setRedrawFrequency(1);
        $progress->start();
        $output->writeln("");
        $output->writeln("Begin Creating Photo...");
        for($i=0; $i< 2; $i++) {
            $photo = new Photo();
            $photo
                ->setImageUrl($this->manager->getImage($this->imageDirPath))
                ->setPhotoId($this->photoManager->createID('PH'));
            $photo = $this->photoManager->createPhoto($photo);

            $storePhoto = new StorePhoto();
            $storePhoto
                ->setPhoto($photo)
                ->setStore($store);

            $storePhoto = $this->storePhotoManager->save($storePhoto, false);
            $store->addStorePhoto($storePhoto);
            $progress->advance();
        }
        $progress->finish();
        $output->writeln("");
        $output->writeln("Finished Creating Photo...");
        $output->writeln("");

        $store = $this->manager->saveStore($store);

        return $store;
    }

    /**
     * @param mixed $categoryManager
     * @return StoreDummy
     */
    public function setCategoryManager($categoryManager)
    {
        $this->categoryManager = $categoryManager;
        return $this;
    }

    /**
     * @param PhotoManager $photoManager
     * @return StoreDummy
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
     * @param StorePhotoManager $storePhotoManager
     * @return StoreDummy
     */
    public function setStorePhotoManager(StorePhotoManager $storePhotoManager): StoreDummy
    {
        $this->storePhotoManager = $storePhotoManager;
        return $this;
    }
}