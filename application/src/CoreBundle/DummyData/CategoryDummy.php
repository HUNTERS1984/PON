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
        $faker = Factory::create();
        $mod = new Category();
        $name = 'Category_'.$i;
        $mod
            ->setCreatedAt(new \DateTime())
            ->setName($name)
            ->setIconUrl($faker->imageUrl(640, 480, 'food'))
            ->setUpdatedAt(new \DateTime());
        $this->manager->dummy($mod);
        return $mod;
    }
}