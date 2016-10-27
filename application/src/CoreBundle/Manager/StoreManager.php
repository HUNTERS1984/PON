<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Store;
use CoreBundle\Entity\Category;

use CoreBundle\Entity\FollowList;
use CoreBundle\Paginator\Pagination;
use Elastica\Filter\Exists;
use Elastica\Filter\GeoDistance;
use Elastica\Filter\MatchAll;
use Elastica\Filter\Missing;
use Elastica\Query;
use Elastica\Query\Term;
use Elastica\Query\Nested;
use Elastica\Query\BoolQuery;
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
     * @var TransformedFinder $couponFinder
     */
    protected $categoryFinder;

    /**
     * @var CategoryManager $categoryManager
     */
    protected $categoryManager;

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
     * get store
     *
     * @param $id
     * @return null | Store
     */
    public function getStore($id)
    {


        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Term(['id'=> ['value' => $id]]));
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

    public function getFollowShop(AppUser $appUser, $params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $mainQuery = new \Elastica\Query;

        $userQuery = new Term(['followLists.appUser.id' => $appUser->getId()]);
        $nestedQuery = new Nested();
        $nestedQuery->setPath("followLists");
        $nestedQuery->setQuery($userQuery);

        $boolQuery = new BoolQuery();
        $boolQuery->addMust($nestedQuery);

        $mainQuery->setPostFilter(new Missing('deletedAt'));
        $mainQuery->setQuery($boolQuery);

        $pagination = $this->storeFinder->createPaginatorAdapter($mainQuery);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();

        return $this->pagination->response($results, $total, $limit, $offset);
    }



    /**
     * get shop follow category
     *
     * @param Category $category
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getShopByCategory(Category $category, $params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;


        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Term(['category.id' => ['value' => $category->getId()]]));

        $pagination = $this->storeFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }
 
    /**
     * List Store
     * @param array $params
     *
     * @return array
     */
    public function listStore($params , $categoryId = 0 ,  array $sortArgs)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $query = new \Elastica\Query;
        $query->setPostFilter(new Missing('deletedAt'));
        if(!empty($sortArgs)){
            $query->setSort($sortArgs);
        }
        if($categoryId > 0){
            $categoryQuery = new Query\Term(['category.id'=> $categoryId]);
            $boolQuery = new BoolQuery();
            $boolQuery->addMust($categoryQuery);
            $query->setQuery($boolQuery);
            $query->addMust($categoryQuery);
        }
        $pagination = $this->storeFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results= $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    public function followShop(AppUser $appUser, Store $shop)
    {
        $followShop = new FollowList();
        $followShop->setStore($shop);
        $followShop->setAppUser($appUser);
        $shop->addFollowList($followShop);
        return $this->saveStore($shop);
    }



    /**
     * getPopularShopByCategory
     *
     * @param Category $category
     * @return array
     */
    public function getPopularShopByCategory(Category $category)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Term(['category.id' => ['value' => $category->getId()]]));
        $query->addSort(['coupons.impression' => ['order' => 'desc']]);
        $result = $this->storeFinder->find($query, 4);
        return $result;
    }

    /**
     * getFullPopularShopByCategory
     *
     * @param Category $category
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getFullPopularShopByCategory(Category $category, $offset, $limit)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Term(['category.id' => ['value' => $category->getId()]]));
        $query->addSort(['coupons.impression' => ['order' => 'desc']]);

        $pagination = $this->storeFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * getNewestShopByCategory
     *
     * @param Category $category
     * @return array
     */
    public function getNewestShopByCategory(Category $category)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $boolQuery = new BoolQuery();
        $boolQuery->addMust(new Term(['category.id' => ['value' => $category->getId()]]));
        $query->setQuery($boolQuery);
        $query->addSort(['createdAt' => ['order' => 'desc']]);
        $result = $this->storeFinder->find($query, 4);

        return $result;
    }

    /**
     * getFullNewestShopByCategory
     *
     * @param Category $category
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getFullNewestShopByCategory(Category $category, $offset, $limit)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $boolQuery = new BoolQuery();
        $boolQuery->addMust(new Term(['category.id' => ['value' => $category->getId()]]));
        $query->setQuery($boolQuery);
        $query->addSort(['createdAt' => ['order' => 'desc']]);

        $pagination = $this->storeFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * getNearestShopByCategory
     *
     * @param Category $category
     * @param $latitude
     * @param $longitude
     * @return array
     */
    public function getNearestShopByCategory(Category $category, $latitude, $longitude)
    {
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
        $mainQuery = new Query\Filtered(new Term(['category.id' => ['value' => $category->getId()]]), $distance);
        $query->setQuery($mainQuery);
        $result = $this->storeFinder->find($query, 4);

        return $result;
    }

    /**
     * getNearestShopByCategory
     *
     * @param Category $category
     * @param $latitude
     * @param $longitude
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getFullNearestShopByCategory(Category $category, $latitude, $longitude, $offset, $limit)
    {
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
        $mainQuery = new Query\Filtered(new Term(['category.id' => ['value' => $category->getId()]]), $distance);
        $query->setQuery($mainQuery);

        $pagination = $this->storeFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }


    /**
     * getApprovedShopByCategory
     *
     * @param Category $category
     * @param AppUser|null $user
     * @return array
     */
    public function getApprovedShopByCategory(Category $category, AppUser $user = null)
    {
        if (!$user) {
            return [];
        }

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));

        $userQuery = new Term(['coupons.useLists.appUser.id' => $user->getId()]);
        $statusQuery = new Term(['coupons.useLists.status' => 1]);

        $nestedQuery = new Nested();
        $subQuery = new BoolQuery();
        $subQuery->addMust($userQuery);
        $subQuery->addMust($statusQuery);
        $nestedQuery->setPath("coupons.useLists");
        $nestedQuery->setQuery($subQuery);

        $mainQuery = new BoolQuery();
        $mainQuery
            ->addMust($nestedQuery)
            ->addMust(new Term(['category.id' => ['value' => $category->getId()]]));

        $query->setQuery($mainQuery);
        $result = $this->storeFinder->find($query, 4);

        return $result;
    }

    /**
     * getFullApprovedShopByCategory
     *
     * @param Category $category
     * @param AppUser|null $user
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getFullApprovedShopByCategory(Category $category, AppUser $user = null,  $offset, $limit)
    {
        if (!$user) {
            return [];
        }

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));

        $userQuery = new Term(['coupons.useLists.appUser.id' => $user->getId()]);
        $statusQuery = new Term(['coupons.useLists.status' => 1]);

        $nestedQuery = new Nested();
        $subQuery = new BoolQuery();
        $subQuery->addMust($userQuery);
        $subQuery->addMust($statusQuery);
        $nestedQuery->setPath("coupons.useLists");
        $nestedQuery->setQuery($subQuery);

        $mainQuery = new BoolQuery();
        $mainQuery
            ->addMust($nestedQuery)
            ->addMust(new Term(['category.id' => ['value' => $category->getId()]]));

        $query->setQuery($mainQuery);

        $pagination = $this->storeFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * getFeaturedShop
     *
     * @param $type
     * @param $params
     * @param AppUser|null $user
     * @return array
     */
    public function getFeaturedShop($type, $params, AppUser $user = null)
    {
        switch ($type) {
            case 2:
                return $this->getNewestShop($params);
            case 3:
                return $this->getNearestShop($params);
            case 4:
                return $this->getApprovedShop($params, $user);
            default:
                return $this->getPopularShop($params);
        }
    }

    /**
     * getFullFeaturedShop
     *
     * @param $type
     * @param $params
     * @param Category $category
     * @param AppUser|null $user
     * @return array
     */
    public function getFullFeaturedShop($type, Category $category, $params, AppUser $user = null)
    {

        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        switch ($type) {
            case 2:
                return $this->getFullNewestShopByCategory($category, $offset, $limit);
            case 3:
                return $this->getFullNearestShopByCategory($category, $params['latitude'], $params['longitude'], $offset, $limit);
            case 4:
                return $this->getFullApprovedShopByCategory($category, $user, $offset, $limit);
            default:
                return $this->getFullPopularShopByCategory($category, $offset, $limit);
        }
    }


    /**
     * getApprovedShop
     *
     * @param $params
     * @param AppUser|null $user
     * @return array
     */
    public function getApprovedShop($params, AppUser $user = null)
    {
        $categories = $this->getCategories($params);

        foreach ($categories['data'] as $key => $item) {
            /** @var Category $category */
            $category = $item;
            $coupons = $this->getApprovedShopByCategory($category, $user);
            $category->setCoupons($coupons);
            $categories['data'][$key] = $category;
        }
        return $categories;
    }

    /**
     * getNearestShop
     *
     * @param $params
     * @return array
     */
    public function getNearestShop($params)
    {
        $latitude = $params['latitude'];
        $longitude = $params['longitude'];

        $categories = $this->getCategories($params);

        foreach ($categories['data'] as $key => $item) {
            /** @var Category $category */
            $category = $item;
            $coupons = $this->getNearestShopByCategory($category, $latitude, $longitude);
            $category->setCoupons($coupons);
            $categories['data'][$key] = $category;
        }
        return $categories;
    }

    /**
     * getNewestShop
     *
     * @param $params
     * @return array
     */
    public function getNewestShop($params)
    {
        $categories = $this->getCategories($params);

        foreach ($categories['data'] as $key => $item) {
            /** @var Category $category */
            $category = $item;
            $coupons = $this->getNewestShopByCategory($category);
            $category->setCoupons($coupons);
            $categories['data'][$key] = $category;
        }
        return $categories;
    }

    /**
     * getPopularShop
     *
     * @param $params
     * @return array
     */
    public function getPopularShop($params)
    {
        $categories = $this->getCategories($params);
        foreach ($categories['data'] as $key => $item) {
            /** @var Category $category */
            $category = $item;
            $coupons = $this->getPopularShopByCategory($category);
            $category->setCoupons($coupons);
            $categories['data'][$key] = $category;
        }
        return $categories;
    }


    /**
     * Get Categories
     * @param array $params
     * @return array
     */
    public function getCategories($params)
    {
        return $this->categoryManager->getCategories($params);
    }


    /**
     * @param CategoryManager $categoryManager
     * @return CouponManager
     */
    public function setCategoryManager($categoryManager)
    {
        $this->categoryManager = $categoryManager;
        return $this;
    }

    /**
     * @param TransformedFinder $categoryFinder
     * @return CouponManager
     */
    public function setCategoryFinder($categoryFinder)
    {
        $this->categoryFinder = $categoryFinder;
        return $this;
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