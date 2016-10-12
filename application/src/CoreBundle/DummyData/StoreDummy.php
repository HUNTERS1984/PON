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
        $lat = $lat + $faker->randomFloat(null,-5,5);
        $long = $long + $faker->randomFloat(null,-5,5);
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