<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\AppUser;
use Faker\Factory;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class AppUserDummy extends BaseDummy implements IDummy
{

    /***
     * @var string $avatarDirPath
     */
    protected $avatarDirPath;


    /**
     * generate dummy data
     */
    public function generate(OutputInterface $output, $i = 0)
    {
        $faker = Factory::create('ja_JP');
        $user = new AppUser();
        $email = 'admin_' . $i . '@pon.dev';
        $role = 'ROLE_ADMIN';

        $user
            ->setCreatedAt(new \DateTime())
            ->setUsername($email)
            ->setAvatarUrl($this->manager->getImage($this->avatarDirPath))
            ->setName($faker->name)
            ->setPlainPassword('admin')
            ->setEmail($email)
            ->setEnabled(true)
            ->setAndroidPushKey($faker->md5)
            ->setApplePushKey($faker->md5)
            ->setTemporaryHash(substr($faker->md5, 0, 4))
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
     * generate dummy data
     */
    public function generateAppUsers(OutputInterface $output,$i = 0)
    {
        $faker = Factory::create('ja_JP');
        $user = new AppUser();
        $email = 'app_' . $i . '@pon.dev';
        $role = 'ROLE_APP';

        $user
            ->setCreatedAt(new \DateTime())
            ->setUsername($email)
            ->setAvatarUrl($this->manager->getImage($this->avatarDirPath))
            ->setName($faker->name)
            ->setPlainPassword('admin')
            ->setEmail($email)
            ->setEnabled(true)
            ->setAndroidPushKey($faker->md5)
            ->setApplePushKey($faker->md5)
            ->setTemporaryHash(substr($faker->md5, 0, 4))
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
    public function generateStoreUsers(OutputInterface $output,$i = 0)
    {
        $faker = Factory::create('ja_JP');
        $user = new AppUser();
        $email = 'store_' . $i . '@pon.dev';

        $user
            ->setCreatedAt(new \DateTime())
            ->setUsername($email)
            ->setAvatarUrl($this->manager->getImage($this->avatarDirPath))
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

    /**
     * @param string $avatarDirPath
     * @return AppUserDummy
     */
    public function setAvatarDirPath(string $avatarDirPath)
    {
        $this->avatarDirPath = $avatarDirPath;
        return $this;
    }
}