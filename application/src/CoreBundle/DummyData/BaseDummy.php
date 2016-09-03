<?php
namespace CoreBundle\DummyData;

use CoreBundle\Manager\AbstractManager;

class BaseDummy
{
    /**
     * @var AbstractManager
     */
    protected $manager;

    public function setManager(AbstractManager $manager)
    {
        $this->manager = $manager;
    }

}