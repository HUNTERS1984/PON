<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\Store;
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
        $name = 'admin_'.$i.'@pon.dev';
        $mod
            ->setName($name)
            ->setLatitude("")
            ->setLongtitude("")
            ->setCreatedAt(new \DateTime())
            ->setUpdatedAt(new \DateTime());
        $this->manager->dummy($mod);
        return $mod;
    }
}