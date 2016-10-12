<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\Store;
use CoreBundle\Manager\CategoryManager;
use CoreBundle\Manager\AppUserManager;
use Faker\Factory;

class StoreDummy extends BaseDummy implements IDummy
{
    /**
     * generate dummy data
     */
    public function generate($i = 0)
    {
        $faker = Factory::create('ja_JP');
        $mod = new Store();
        $name = $faker->name;


        $categoryId = ($i % 10) + 1;
        $cateData = $this->categoryManager->findOneById($categoryId);


        $userId = ($i % 10) + 1;
        $userData = $this->userManager->findOneById($userId);
        $lat = 10.785871;
        $long = 106.6851;
        $lat = $lat + $faker->randomFloat(null,-5,5);
        $long = $long + $faker->randomFloat(null,-5,5);
        $mod
            ->setName($name)
            ->setCategory($cateData)
            ->setUser($userData)
            ->setLatitude($lat)
            ->setLongtitude($long)
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());
        $this->manager->dummy($mod);
        return $mod;
    }
}