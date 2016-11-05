<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Photo;
use CoreBundle\Entity\Store;
use CoreBundle\Entity\StorePhoto;
use CoreBundle\Manager\CategoryManager;
use CoreBundle\Manager\AppUserManager;
use CoreBundle\Manager\PhotoManager;
use CoreBundle\Manager\UserManager;
use Faker\Factory;

class StoreDummy extends BaseDummy implements IDummy
{
    /** @var AppUserManager $appUserManager */
    private $appUserManager;

    /** @var CategoryManager $categoryManager */
    private $categoryManager;

    /** @var PhotoManager $photoManager */
    private $photoManager;

    /**
     * generate dummy data
     */
    public function generate()
    {
        $faker = Factory::create('ja_JP');
        $store = new Store();
        $name = $faker->name;
        $categoryId = $faker->numberBetween(1, 5);
        $category = $this->categoryManager->findOneById($categoryId);
        $userId = $faker->numberBetween(5, 10);
        /**@var AppUser $user */
        $user = $this->appUserManager->findOneById($userId);
        
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
            ->setAvatarUrl($faker->imageUrl(640, 480, 'food'))
            ->setTel($faker->phoneNumber)
            ->setLatitude($lat)
            ->setLongitude($long)
            ->setAddress($faker->address)
            ->setCloseDate("土曜日と日曜日")
            ->setAveBill($faker->numberBetween(1,100))
            ->setHelpText($faker->paragraph(3))
            ->setCategory($category)
            ->setAppUser($user);

        /** @var Store $store*/
        $store = $this->manager->createStore($store);
        for($i=0; $i< 5; $i++) {
            $photo = new Photo();
            $photo
                ->setImageUrl($faker->imageUrl(640, 480, 'food'));
            $photo = $this->photoManager->createPhoto($photo);

            $storePhoto = new StorePhoto();
            $storePhoto
                ->setPhoto($photo)
                ->setStore($store);
            $store->addStorePhoto($storePhoto);
        }

        $store = $this->manager->saveStore($store);
        return $store;
    }

    /**
     * @param mixed $appUserManager
     * @return StoreDummy
     */
    public function setAppUserManager($appUserManager)
    {
        $this->appUserManager = $appUserManager;
        return $this;
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
}