<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\Store;
use CoreBundle\Entity\Segement;
use CoreBundle\Paginator\Pagination;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class SegementManager extends AbstractManager
{
    /**
     * @var Pagination
     */
    protected $pagination;

    /**
     * @var TransformedFinder $segementFinder
     */
    protected $segementFinder;

    /**
     * @var Pagination $pagination
     */
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    public function dummy(Segement $segement)
    {
        $this->save($segement);
    }

    /**
     * @param Segement $segement
     *
     * @return Segement
     */
    public function createSegement(Segement $segement)
    {
        $segement
            ->setCreatedAt(new \DateTime());
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
    public function deleteSegment(Segement $segement)
    {
        $segement
            ->setDeletedAt(new \DateTime());
        return $this->saveSegement($segement);
    }

    /**
     * @param mixed $segementFinder
     * @return SegementManager
     */
    public function setSegementFinder($segementFinder)
    {
        $this->segementFinder = $segementFinder;
        return $this;
    }

}