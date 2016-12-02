<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\NewsCategory;
use CoreBundle\Paginator\Pagination;
use Elastica\Aggregation\Nested;
use Elastica\Aggregation\ValueCount;
use Elastica\Filter\Missing;
use Elastica\Query;
use Elastica\Query\Term;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class NewsCategoryManager extends AbstractManager
{
    /**
     * @var Pagination
     */
    protected $pagination;

    /**
     * @var TransformedFinder $newsCategoryFinder
     */
    protected $newsCategoryFinder;

    /**
     * @var Pagination $pagination
     */
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    public function dummy(NewsCategory $newsCategory)
    {
        $this->save($newsCategory);
    }

    /**
     * @param NewsCategory $newsCategory
     *
     * @return NewsCategory
     */
    public function saveNewsCategory(NewsCategory $newsCategory)
    {
        $newsCategory->setUpdatedAt(new \DateTime());
        return $this->save($newsCategory);
    }

    /**
     * @param NewsCategory $newsCategory
     *
     * @return NewsCategory
     */
    public function createNewsCategory(NewsCategory $newsCategory)
    {
        $newsCategory->setCreatedAt(new \DateTime());
        $this->saveNewsCategory($newsCategory);
    }

    /**
     * @param NewsCategory $newsCategory
     *
     * @return boolean
     */
    public function deleteNewsCategory(NewsCategory $newsCategory)
    {
        $newsCategory
            ->setDeletedAt(new \DateTime());
        return $this->saveNewsCategory($newsCategory);
    }


    /**
     * @param TransformedFinder $newsCategoryFinder
     * @return NewsCategoryManager
     */
    public function setNewsCategoryFinder($newsCategoryFinder)
    {
        $this->newsCategoryFinder = $newsCategoryFinder;
        return $this;
    }

}