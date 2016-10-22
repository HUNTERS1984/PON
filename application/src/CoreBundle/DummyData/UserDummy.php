<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\User;
use Faker\Factory;

class UserDummy extends BaseDummy implements IDummy
{
    /**
     * generate dummy data
     */
    public function generate($i = 0)
    {
        $faker = Factory::create();
        $user = new User();
        $email = 'user_'.$i;
        $user
            ->setCreatedAt(new \DateTime())
            ->setFullname($email)
            ->setEmail($email)
            ->setEmailCanonical($email)
            ->setUsername($email)
            ->setUsernameCanonical($email)
            ->setPassword($faker->md5)
            ->setSex(1)
            ->setBirthday(new \DateTime())
            ->setLocale("")
            ->setCompany("")
            ->setAddress("")
            ->setTel("")
            ->setImageUser("")

            ->setRememberToken($faker->md5)
            ->setTemporaryHash($faker->md5)
            ->setConfirmationToken($faker->md5)
            ->setUpdatedAt(new \DateTime());
        $this->manager->dummy($user);
        return $user;
    }
}