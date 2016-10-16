<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
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
        $this->saveStore($store);
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
     * is follow
     *
     * @param AppUser $user
     * @param Store $store
     * @return bool
     */
    public function isFollow(AppUser $user, Store $store)
    {
        $storeQuery = new Query\Term(['id'=> $store->getId()]);
        $userQuery = new Query\Term(['followLists.appUser.id'=> $user->getId()]);
        $nestedQuery = new Query\Nested();
        $nestedQuery->setPath("followLists");
        $nestedQuery->setQuery($userQuery);
        $query = new Query\BoolQuery();
        $query
            ->addMust($storeQuery)
            ->addMust($nestedQuery);
        $store = $this->storeFinder->find($query);
        if(!$store) {
            return false;
        }

        return true;
    }

    /**
     * List Store
     * @param array $params
     *
     * @return array
     */
    public function listStore($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $conditions = [];
        if (isset($params['name'])) {
            $conditions = [
                'name' => [
                    'type' => 'like',
                    'value' => "%" . $params['name'] . "%"
                ]
            ];
        }

        $conditions['deletedAt'] = [
            'type' => 'is',
            'value' => 'NULL'
        ];

        $orderBy = ['createdAt' => 'DESC'];

        $query = $this->getQuery($conditions, $orderBy, $limit, $offset);

        return $this->pagination->render($query, $limit, $offset);
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