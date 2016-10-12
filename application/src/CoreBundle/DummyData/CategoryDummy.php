<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\Category;
use Faker\Factory;

class CategoryDummy extends BaseDummy implements IDummy
{
    /**
     * generate dummy data
     */
    public function generate($i = 0)
    {
        $faker = Factory::create('ja_JP');
        $mod = new Category();
        $name = $faker->name;
        $mod
            ->setCreatedAt(new \DateTime())
            ->setName($name)
            ->setIconUrl($faker->imageUrl(640, 480, 'food'))
            ->setUpdatedAt(new \DateTime());
        $this->manager->dummy($mod);
        return $mod;
    }
}