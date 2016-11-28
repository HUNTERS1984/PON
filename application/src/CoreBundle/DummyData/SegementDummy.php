<?php

namespace CoreBundle\DummyData;

use CoreBundle\Entity\Segement;
use CoreBundle\Entity\Store;
use CoreBundle\Manager\SegementManager;
use CoreBundle\Manager\StoreManager;
use Faker\Factory;
use Symfony\Component\Console\Output\OutputInterface;

class SegementDummy extends BaseDummy implements IDummy
{
    /**@var StoreManager */
    private $storeManager;

    /** @var SegementManager $segementManager*/
    private $segementManager;

    /**
     * generate dummy data
     */
    public function generate(OutputInterface $output, $i = 0)
    {
        $faker = Factory::create('ja_JP');
        $storeId = $faker->numberBetween(1,10);
        $title =  $faker->name;

        $store = $this->storeManager->findOneById($storeId);

        $segement = new Segement();
        $segement->setTitle($title);
        $segement->setStore($store);

        $segement = $this->segementManager->createSegement($segement);
        return $segement;
    }

    /**
     * @param StoreManager $storeManager
     * @return SegementDummy
     */
    public function setStoreManager($storeManager)
    {
        $this->storeManager = $storeManager;
        return $this;
    }

    /**
     * @param SegementManager $segementManager
     * @return SegementDummy
     */
    public function setSegementManager($segementManager)
    {
        $this->segementManager = $segementManager;
        return $this;
    }
}