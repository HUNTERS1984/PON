<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Category;
use CoreBundle\Entity\FollowList;
use CoreBundle\Entity\Store;
use CoreBundle\Paginator\Pagination;
use Elastica\Filter\Exists;
use Elastica\Filter\GeoDistance;
use Elastica\Filter\MatchAll;
use Elastica\Filter\Missing;
use Elastica\Query;
use Elastica\QueryBuilder\DSL\Filter;
use Elastica\Test\Filter\MissingTest;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class StoreManager extends AbstractManager
{
    /**
     * @var Pagination
     */
    protected $pagination;

    /**
     * @var TransformedFinder $storeFinder
     */
    protected $storeFinder;

    /**
     * @var Pagination $pagination
     */
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    public function dummy(Store $store)
    {
        $this->save($store);
    }

    /**
     * @param Store $store
     *
     * @return Store
     */
    public function createStore(Store $store)
    {
        $store->setCreatedAt(new \DateTime());
        $store->setImpression(0);
        return $this->saveStore($store);
    }

    /**
     * @param Store $store
     *
     * @return Store
     */
    public function saveStore(Store $store)
    {
        $store->setUpdatedAt(new \DateTime());
        return $this->save($store);
    }

    /**
     * @param Store $store
     *
     * @return boolean
     */
    public function deleteStore(Store $store)
    {
        $store
            ->setDeletedAt(new \DateTime());
        return $this->saveStore($store);
    }

    /**
     * get store
     *
     * @param $id
     * @return null | Store
     */
    public function getStore($id)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Query\Term(['id' => ['value' => $id]]));
        $result = $this->storeFinder->find($query);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Filter Shop By Map
     *
     * @param array $params
     *
     * @return array
     */
    public function filterShopByMap($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $distance = new GeoDistance(
            'location',
            [
                'lat' => $params['latitude'],
                'lon' => $params['longitude']
            ],
            '1km'
        );
        $query = new \Elastica\Query;
        $all = new Query\MatchAll();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Query\Filtered($all, $distance));
        $pagination = $this->storeFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results= $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * get shop count
     *
     * @param Category $category
     * @return integer
     */
    public function getShopCount(Category $category)
    {
        $categoryQuery = new Query\Term(['category.id'=> $category->getId()]);
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery($categoryQuery);

        $pagination = $this->storeFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults(0, 10);
        $total = $transformedPartialResults->getTotalHits();
        return $total;
    }

    /**
     * getFeaturedStore
     *
     * @param $type
     * @param $params
     * @param Category|null $category
     * @return array
     */
    public function getFeaturedStore($type, $params, Category $category = null)
    {
        switch ($type) {
            case 2:
                return $this->getNewestStore($params, $category);
            case 3:
                return $this->getNearestStore($params, $category);
            default:
                return $this->getPopularStore($params, $category);
        }
    }
    /**
     * getNearestStore
     *
     * @param $params
     * @param Category|null $category
     * @return array
     */
    public function getNearestStore($params, Category $category = null)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $latitude = $params['latitude'];
        $longitude = $params['longitude'];

        $distance = new GeoDistance(
            'location',
            [
                'lat' => $latitude,
                'lon' => $longitude
            ],
            '1km'
        );

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $mainQuery = new Query\Filtered(null, $distance);
        $boolQuery = new Query\BoolQuery();
        $boolQuery->addMust($mainQuery);
        if($category) {
            $boolQuery->addMust(new Query\Term(['category.id' => ['value' => $category->getId()]]));
        }
        $query->setQuery($boolQuery);

        $pagination = $this->storeFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * getNewestStore
     *
     * @param $params
     * @param Category|null $category
     *
     * @return array
     */
    public function getNewestStore($params, Category $category = null)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['createdAt' => ['order' => 'desc']]);
        if($category) {
            $query->setQuery(new Query\Term(['category.id' => ['value' => $category->getId()]]));
        }

        $pagination = $this->storeFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);

    }

    /**
     * getPopularStore
     *
     * @param $params
     * @param Category|null $category
     *
     * @return array
     */
    public function getPopularStore($params, Category $category = null)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['impression' => ['order' => 'desc']]);

        if($category) {
            $query->setQuery(new Query\Term(['category.id' => ['value' => $category->getId()]]));
        }

        $pagination = $this->storeFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }


    /**
     * List Store From Admin
     * @param array $params
     *
     * @return array
     */
    public function listStoreFromAdmin($params)
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
            $multiMatchQuery->setFields(['title']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery->addMust($multiMatchQuery);
        } else {
            $boolQuery->addMust(new Query\MatchAll());
        }

        $query->setQuery($boolQuery);

        $pagination = $this->storeFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * List Store From Client
     * @param array $params
     * @param AppUser $user
     *
     * @return array
     */
    public function listStoreFromClient($params, AppUser $user)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'desc';
        $sortBy = isset($params['sort_by']) && in_array($params['sort_by'], ['title', 'createdAt']) ? $params['sort_by'] : 'updatedAt';
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort([$sortBy => ['order' => $orderBy]]);

        $boolQuery = new Query\BoolQuery();
        if (!empty($queryString)) {
            $multiMatchQuery = new Query\MultiMatch();
            $multiMatchQuery->setFields(['title']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery->addMust($multiMatchQuery);
        } else {
            $boolQuery->addMust(new Query\MatchAll());
        }

        $boolQuery->addMust(new Query\Term(['appUser.id' => $user->getId()]));

        $query->setQuery($boolQuery);

        $pagination = $this->storeFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset, $sortBy, $orderBy);
    }

    /**
     * @param mixed $storeFinder
     * @return StoreManager
     */
    public function setStoreFinder($storeFinder)
    {
        $this->storeFinder = $storeFinder;
        return $this;
    }

}