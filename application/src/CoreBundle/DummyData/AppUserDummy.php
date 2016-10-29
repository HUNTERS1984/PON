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
        $faker = Factory::create('ja_JP');
        $user = new AppUser();
        $email = 'admin_' . $i . '@pon.dev';
        $role = $faker->randomElement(['ROLE_ADMIN', 'ROLE_APP']);
       
        $user
            ->setCreatedAt(new \DateTime())
            ->setUsername($email)
            ->setName($faker->name)
            ->setPlainPassword('admin')
            ->setEmail($email)
            ->setEnabled(true)
            ->setAndroidPushKey($faker->md5)
            ->setApplePushKey($faker->md5)
            ->setTemporaryHash($faker->md5)
            ->setRoles([$role])
            ->setLocale($faker->locale)
            ->setCompany($faker->company)
            ->setAddress($faker->address)
            ->setTel($faker->phoneNumber)
            ->setUpdatedAt(new \DateTime());
        $this->manager->dummy($user);
        return $user;
    }

    /**
     * generateStoreUsers
     */
    public function generateStoreUsers($i = 0)
    {
        $faker = Factory::create('ja_JP');
        $user = new AppUser();
        $email = 'store_' . $i . '@pon.dev';

        $user
            ->setCreatedAt(new \DateTime())
            ->setUsername($email)
            ->setName($faker->name)
            ->setPlainPassword('admin')
            ->setEmail($email)
            ->setEnabled(true)
            ->setAndroidPushKey($faker->md5)
            ->setApplePushKey($faker->md5)
            ->setTemporaryHash($faker->md5)
            ->setRoles(['ROLE_CLIENT'])
            ->setLocale($faker->locale)
            ->setCompany($faker->company)
            ->setAddress($faker->address)
            ->setTel($faker->phoneNumber)
            ->setUpdatedAt(new \DateTime());
        $this->manager->dummy($user);
        return $user;
    }
}