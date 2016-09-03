<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\User;
use Faker\Factory;

class UserDummy extends BaseDummy implements IDummy
{
    /**
     * generate dummy data
     */
    public function generate()
    {
        $faker = Factory::create();
        $user = new User();
        $user->setUsername('admin')
            ->setPassword('admin')
            ->setEmail($faker->email)
            ->setActived(true)
            ->setRole('Admin');
        $this->manager->dummy($user);
        return $user;
    }
}