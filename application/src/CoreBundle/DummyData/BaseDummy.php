<?php
namespace CoreBundle\DummyData;

use CoreBundle\Manager\AbstractManager;

class BaseDummy
{
    /**
     * @var AbstractManager
     */
    protected $manager;
    protected $categoryManager;
    protected $appUserManager;
    protected $storeManager;

    public function setManager(AbstractManager $manager)
    {

        $this->manager = $manager;
    }

    public function setCategoryManager(AbstractManager $manager)
    {
        $this->categoryManager = $manager;

    }
    public function setAppUserManager(AbstractManager $manager)
    {
        $this->appUserManager = $manager;
    }

    public function setUserManager(AbstractManager $manager)
    {
        $this->userManager = $manager;
    }

    public function setStoreManager(AbstractManager $manager)
    {
        $this->storeManager = $manager;
    }



}