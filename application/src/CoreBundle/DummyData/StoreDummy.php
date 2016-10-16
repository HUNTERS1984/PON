<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\Store;
use CoreBundle\Manager\CategoryManager;
use CoreBundle\Manager\AppUserManager;
use CoreBundle\Manager\UserManager;
use Faker\Factory;

class StoreDummy extends BaseDummy implements IDummy
{
    /** @var UserManager $userManager */
    private $userManager;

    /** @var CategoryManager $categoryManager */
    private $categoryManager;

    /**
     * generate dummy data
     */
    public function generate($i = 0)
    {
        $faker = Factory::create('ja_JP');
        $store = new Store();
        $name = $faker->name;
        $categoryId = ($i % 10) + 1;
        $category = $this->categoryManager->findOneById($categoryId);
        $userId = ($i % 10) + 1;
        $user = $this->userManager->findOneById($userId);
        $lat = 10.785871;
        $long = 106.6851;
        $arrayGeo = [
            [10.785871, 106.6851],
            [10.784338, 106.684574],
            [10.788322, 106.685196],
            [10.786783, 106.683758],
            [10.785076, 106.682965],
            [10.785919, 106.686226],
            [10.839665, 106.779503],
            [10.839812, 106.780339],
            [10.840795, 106.778982],
            [10.840592, 106.777550],
        ];
        $location = $faker->numberBetween(0,9);
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
            ->setUser($user)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());
        $this->manager->dummy($store);
        return $store;
    }

    /**
     * @param mixed $userManager
     * @return StoreDummy
     */
    public function setUserManager($userManager)
    {
        $this->userManager = $userManager;
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
}