<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\AppUser;
use Faker\Factory;

class AppUserDummy extends BaseDummy implements IDummy
{
    /**
     * generate dummy data
     */
    public function generate($i = 0)
    {
        $faker = Factory::create();
        $user = new AppUser();
        $email = 'admin_'.$i.'@pon.dev';
        $user
            ->setCreatedAt(new \DateTime())
            ->setUsername($email)
            ->setPlainPassword('admin')
            ->setEmail($email)
            ->setEnabled(true)
            ->setAndroidPushKey($faker->md5)
            ->setApplePushKey($faker->md5)
            ->setTemporaryHash($faker->md5)
            ->setUpdatedAt(new \DateTime());
        $this->manager->dummy($user);
        return $user;
    }
}