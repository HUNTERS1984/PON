<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\AppUser;
use CoreBundle\Manager\StoreManager;
use Faker\Factory;
use Symfony\Component\Console\Output\OutputInterface;

class AppUserDummy extends BaseDummy implements IDummy
{

    /***
     * @var string $avatarDirPath
     */
    protected $avatarDirPath;

    /** @var StoreManager */
    private $storeManager;


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
            ->setStore(null)
            ->setAvatarUrl($this->manager->getImage($this->avatarDirPath))
            ->setName($faker->name)
            ->setPlainPassword('admin')
            ->setEmail($email)
            ->setGender($faker->numberBetween(0,2))
            ->setEnabled(true)
            ->setRoles([$role])
            ->setCompany($faker->company)
            ->setAddress($faker->address)
            ->setTel($faker->phoneNumber);
        $this->manager->createAppUser($user);
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
            ->setStore(null)
            ->setAvatarUrl($this->manager->getImage($this->avatarDirPath))
            ->setName($faker->name)
            ->setPlainPassword('admin')
            ->setEmail($email)
            ->setGender($faker->numberBetween(0,2))
            ->setEnabled(true)
            ->setRoles([$role])
            ->setCompany($faker->company)
            ->setAddress($faker->address)
            ->setTel($faker->phoneNumber);
        $this->manager->createAppUser($user);
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

        $storeId = $faker->numberBetween(1,10);
        $store = $this->storeManager->findOneById($storeId);

        $user
            ->setCreatedAt(new \DateTime())
            ->setStore($store)
            ->setUsername($email)
            ->setAvatarUrl($this->manager->getImage($this->avatarDirPath))
            ->setName($faker->name)
            ->setPlainPassword('admin')
            ->setGender($faker->numberBetween(0,2))
            ->setEmail($email)
            ->setEnabled(true)
            ->setRoles(['ROLE_CLIENT'])
            ->setCompany($faker->company)
            ->setAddress($faker->address)
            ->setTel($faker->phoneNumber);
        $this->manager->createAppUser($user);
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

    /**
     * @param StoreManager $storeManager
     * @return AppUserDummy
     */
    public function setStoreManager($storeManager)
    {
        $this->storeManager = $storeManager;
        return $this;
    }

}