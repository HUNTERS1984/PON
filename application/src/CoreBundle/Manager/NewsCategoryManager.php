<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
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
        if(!$newsCategory->getNewsCategoryId()) {
            $newsCategory->setNewsCategoryId($this->createID('NC'));
        }

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

    /**
     * Get NewsCategories
     * @param array $params
     *
     * @return array
     */
    public function getNewsCategories($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['createdAt' => ['order' => 'desc']]);

        $boolQuery = new Query\BoolQuery();

        if (!empty($queryString)) {
            $multiMatchQuery = new Query\MultiMatch();
            $multiMatchQuery->setFields(['name']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery
                ->addMust($multiMatchQuery);
        } else {
            $boolQuery
                ->addMust(new Query\MatchAll());
        }

        $query->setQuery($boolQuery);

        $pagination = $this->newsCategoryFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * get store
     *
     * @param $id
     * @return null | NewsCategory
     */
    public function getNewsCategory($id)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Query\Term(['id' => ['value' => $id]]));
        $result = $this->newsCategoryFinder->find($query);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * List NewsCategory From Admin
     * @param array $params
     *
     * @return array
     */
    public function listNewsCategoryFromAdmin($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['createdAt' => ['order' => 'desc']]);

        $boolQuery = new Query\BoolQuery();
        if (!empty($queryString)) {
            $multiMatchQuery = new Query\MultiMatch();
            $multiMatchQuery->setFields(['name']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery->addMust($multiMatchQuery);
        } else {
            $boolQuery->addMust(new Query\MatchAll());
        }

        $query->setQuery($boolQuery);

        $pagination = $this->newsCategoryFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * List NewsCategory From Client
     * @param array $params
     * @param AppUser $user
     *
     * @return array
     */
    public function listNewsCategoryFromClient($params, AppUser $user)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'desc';
        $sortBy = isset($params['sort_by']) && in_array($params['sort_by'], ['name', 'createdAt']) ? $params['sort_by'] : 'updatedAt';
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort([$sortBy => ['order' => $orderBy]]);

        $boolQuery = new Query\BoolQuery();
        if (!empty($queryString)) {
            $multiMatchQuery = new Query\MultiMatch();
            $multiMatchQuery->setFields(['name']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery->addMust($multiMatchQuery);
        } else {
            $boolQuery->addMust(new Query\MatchAll());
        }

        $boolQuery->addMust(new Query\Term(['id' => $user->getStore()->getId()]));

        $query->setQuery($boolQuery);

        $pagination = $this->newsCategoryFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset, $sortBy, $orderBy);
    }

}