<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Segement;
use CoreBundle\Entity\Store;
use Elastica\Filter\Missing;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class SegementManager extends AbstractManager
{
    /**
     * @var TransformedFinder $storeFinder
     */
    protected $segementFinder;

    /**
     * @param Segement $segement
     *
     * @return Segement
     */
    public function createSegement(Segement $segement)
    {
        $segement->setCreatedAt(new \DateTime());
        return $this->saveSegement($segement);
    }

    /**
     * @param Segement $segement
     *
     * @return Segement
     */
    public function saveSegement(Segement $segement)
    {
        $segement->setUpdatedAt(new \DateTime());
        return $this->save($segement);
    }

    /**
     * @param Segement $segement
     *
     * @return boolean
     */
    public function deleteSegement(Segement $segement)
    {
        $segement
            ->setDeletedAt(new \DateTime());
        return $this->saveSegement($segement);
    }

    public function getSegementFromClient(AppUser $user)
    {
        $storeIds = array_map(function (Store $store) {
            return $store->getId();
        }, $user->getStores()->toArray());

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['updatedAt' => ['order' => 'desc']]);
        $query->setSize(1000);

        $query->setQuery(new Query\Terms('store.id',$storeIds));

        $results = $this->segementFinder->find($query);

        $segements = [];
        foreach($results as $item) {
            /** @var Segement $segement */
            $segement = $item;
            $segements[$segement->getId()] = $segement->getTitle();
        }
        return $segements;

    }

    public function getSegementFromAdmin()
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['updatedAt' => ['order' => 'desc']]);
        $query->setSize(1000);

        $results = $this->segementFinder->find($query);
        $segements = [];
        foreach($results as $item) {
            /** @var Segement $segement */
            $segement = $item;
            $segements[$segement->getId()] = $segement->getTitle();
        }
        return $segements;

    }


    /**
     * @param TransformedFinder $segementFinder
     * @return Segement
     */
    public function setSegementFinder($segementFinder)
    {
        $this->segementFinder = $segementFinder;
        return $this;
    }

}