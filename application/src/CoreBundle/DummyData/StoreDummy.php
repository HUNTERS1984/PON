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
        $faker = Factory::create();
        $mod = new Store();
        $name = 'store_'.$i;


        $categoryId = ($i % 10) + 1;
        $cateData = $this->categoryManager->findOneById($categoryId);


        $userId = ($i % 10) + 1;
        $userData = $this->userManager->findOneById($userId);

        $mod
            ->setName($name)
            ->setCategory($cateData)
            ->setUser($userData)
            ->setLatitude("")
            ->setLongtitude("")
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());
        $this->manager->dummy($mod);
        return $mod;
    }
}