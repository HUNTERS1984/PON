<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Category;
use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\LikeList;
use CoreBundle\Entity\UseList;
use CoreBundle\Entity\Store;
use CoreBundle\Entity\UseList;
use CoreBundle\Paginator\Pagination;
use Doctrine\ORM\QueryBuilder;
use Elastica\Filter\GeoDistance;
use Elastica\Filter\Missing;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Filter\Exists;
use Elastica\Filter\GeoDistance;
use Elastica\Query\Match;
use Elastica\Query\MatchAll;
use Elastica\Query\MultiMatch;
use Elastica\Query\Term;
use Elastica\Query\Nested;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class CouponManager extends AbstractManager
{
    /**
     * @var Pagination
     */
    protected $pagination;

    /**
     * @var TransformedFinder $couponFinder
     */
    protected $couponFinder;

    /**
     * @var TransformedFinder $couponFinder
     */
    protected $categoryFinder;

    /**
     * @var Pagination $pagination
     */
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    public function dummy(Coupon $coupon)
    {
        $this->save($coupon);
    }

    /**
     * @param Coupon $coupon
     *
     * @return Coupon
     */
    public function createCoupon(Coupon $coupon)
    {
        $coupon->setCreatedAt(new \DateTime());
        $this->saveCoupon($coupon);
    }

    /**
     * @param Coupon $coupon
     *
     * @return Coupon
     */
    public function saveCoupon(Coupon $coupon)
    {
        $coupon->setUpdatedAt(new \DateTime());
        return $this->save($coupon);
    }

    /**
     * getPopularCouponsByCategory
     *
     * @param Category $category
     * @return array
     */
    public function getPopularCouponsByCategory(Category $category)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Term(['store.category.id'=> ['value' => $category->getId()]]));
        $query->addSort(['impression' => ['order' => 'desc']]);
        $result = $this->couponFinder->find($query, 4);
        return $result;
    }

    /**
     * getNewestCouponsByCategory
     *
     * @param Category $category
     * @return array
     */
    public function getNewestCouponsByCategory(Category $category)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $boolQuery = new BoolQuery();
        $boolQuery->addMust(new Term(['store.category.id'=> ['value' => $category->getId()]]));
        $query->setQuery($boolQuery);
        $query->addSort(['createdAt' => ['order' => 'desc']]);
        $result = $this->couponFinder->find($query, 4);

        return $result;
    }

    /**
     * getNearestCouponsByCategory
     *
     * @param Category $category
     * @param $latitude
     * @param $longitude
     * @return array
     */
    public function getNearestCouponsByCategory(Category $category, $latitude, $longitude)
    {
        $distance = new GeoDistance(
            'store.location',
            [
                'lat' => $latitude,
                'lon' => $longitude
            ],
            '1km'
        );

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $mainQuery = new Query\Filtered(new Term(['store.category.id'=> ['value' => $category->getId()]]), $distance);
        $query->setQuery($mainQuery);
        $result = $this->couponFinder->find($query, 4);

        return $result;
    }

    /**
     * getApprovedCouponsByCategory
     *
     * @param Category $category
     * @param AppUser|null $user
     * @return array
     */
    public function getApprovedCouponsByCategory(Category $category, AppUser $user = null)
    {
        if(!$user) {
            return [];
        }

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));

        $userQuery = new Term(['useLists.appUser.id' => $user->getId()]);
        $statusQuery = new Term(['useLists.status' => 1]);

        $nestedQuery = new Nested();
        $subQuery = new BoolQuery();
        $subQuery->addMust($userQuery);
        $subQuery->addMust($statusQuery);
        $nestedQuery->setPath("useLists");
        $nestedQuery->setQuery($subQuery);

        $mainQuery = new BoolQuery();
        $mainQuery
            ->addMust($nestedQuery)
            ->addMust(new Term(['store.category.id'=> ['value' => $category->getId()]]));

        $query->setQuery($mainQuery);
        $result = $this->couponFinder->find($query, 4);

        return $result;
    }

    /**
     * getFeaturedCoupon
     *
     * @param $type
     * @param $params
     * @param AppUser|null $user
     * @return array
     */
    public function getFeaturedCoupon($type, $params, AppUser $user = null)
    {
        switch ($type)
        {
            case 2:
                return $this->getNewestCoupon($params);
            case 3:
                return $this->getNearestCoupon($params);
            case 4:
                return $this->getApprovedCoupon($params, $user);
            default:
                return $this->getPopularCoupon($params);
        }
    }

    /**
     * getApprovedCoupon
     *
     * @param $params
     * @param AppUser|null $user
     * @return array
     */
    public function getApprovedCoupon($params, AppUser $user = null)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;


        $categories = $this->getCategories($limit, $offset);

        foreach($categories['data'] as $key => $item) {
            /** @var Category $category */
            $category = $item;
            $coupons = $this->getApprovedCouponsByCategory($category, $user);
            $category->setCoupons($coupons);
            $categories['data'][$key] = $category;
        }
        return $categories;
    }

    /**
     * getNearestCoupon
     *
     * @param $params
     * @return array
     */
    public function getNearestCoupon($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $latitude = $params['latitude'];
        $longitude = $params['longitude'];

        $categories = $this->getCategories($limit, $offset);

        foreach($categories['data'] as $key => $item) {
            /** @var Category $category */
            $category = $item;
            $coupons = $this->getNearestCouponsByCategory($category, $latitude, $longitude);
            $category->setCoupons($coupons);
            $categories['data'][$key] = $category;
        }
        return $categories;
    }

    /**
     * getNewestCoupon
     *
     * @param $params
     * @return array
     */
    public function getNewestCoupon($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;


        $categories = $this->getCategories($limit, $offset);

        foreach($categories['data'] as $key => $item) {
            /** @var Category $category */
            $category = $item;
            $coupons = $this->getNewestCouponsByCategory($category);
            $category->setCoupons($coupons);
            $categories['data'][$key] = $category;
        }
        return $categories;
    }


    /**
     * Get Categories
     *
     * @return array
     */
    public function getCategories($limit, $offset)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['name' => ['order' => 'asc']]);

        $pagination = $this->categoryFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * getPopularCoupon
     *
     * @param $params
     * @return array
     */
    public function getPopularCoupon($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;


        $categories = $this->getCategories($limit, $offset);
        foreach($categories['data'] as $key => $item) {
            /** @var Category $category */
            $category = $item;
            $coupons = $this->getPopularCouponsByCategory($category);
            $category->setCoupons($coupons);
            $categories['data'][$key] = $category;
        }
        return $categories;
    }

    /**
     * @param Coupon $coupon
     *
     * @return boolean
     */
    public function deleteCoupon(Coupon $coupon)
    {
        $coupon
            ->setDeletedAt(new \DateTime());
        return $this->saveCoupon($coupon);
    }

    /**
     * is like
     *
     * @param AppUser $user
     * @param Coupon $coupon
     * @return bool
     */
    public function isLike(AppUser $user = null, Coupon $coupon)
    {
        if (!$user) {
            return false;
        }
        $couponQuery = new Term(['id' => $coupon->getId()]);
        $userQuery = new Term(['likeLists.appUser.id' => $user->getId()]);
//        $nestedQuery = new Nested();
//        $nestedQuery->setPath("likeLists");
//        $nestedQuery->setQuery($userQuery);
        $query = new BoolQuery();
        $query
            ->addMust($couponQuery)
            ->addMust($userQuery);
        $store = $this->couponFinder->find($query);
        if (!$store) {
            return false;
        }

        return true;
    }

    /**
     * is used
     *
     * @param AppUser $user
     * @param Coupon $coupon
     * @return bool
     */
    public function isUsed(AppUser $user = null, Coupon $coupon)
    {
        if (!$user) {
            return false;
        }
        $couponQuery = new Term(['id' => $coupon->getId()]);
        $userQuery = new Term(['useLists.appUser.id' => $user->getId()]);
//        $nestedQuery = new Nested();
//        $nestedQuery->setPath("useLists");
//        $nestedQuery->setQuery($userQuery);
        $query = new BoolQuery();
        $query
            ->addMust($couponQuery)
            ->addMust($userQuery);
        $store = $this->couponFinder->find($query);
        if (!$store) {
            return false;
        }

        return true;
    }

    /**
     * is can use
     *
     * @param AppUser $user
     * @param Coupon $coupon
     * @return bool
     */
    public function isCanUse(AppUser $user = null, Coupon $coupon)
    {
        if(!$coupon->isNeedLogin()) {
            return true;
        }

        if (!$user) {
            return false;
        }
        $couponQuery = new Term(['id' => $coupon->getId()]);
        $userQuery = new Term(['useLists.appUser.id' => $user->getId()]);
        $statusQuery = new Term(['useLists.status' => 1]);
        $mainQuery = new BoolQuery();
        $mainQuery->addMust($userQuery);
        $mainQuery->addMust($statusQuery);

//        $nestedQuery = new Nested();
//        $nestedQuery->setPath("useLists");
//        $nestedQuery->setQuery($mainQuery);
        $query = new BoolQuery();
        $query
            ->addMust($couponQuery)
            ->addMust($mainQuery);
        $store = $this->couponFinder->find($query);
        if (!$store) {
            return false;
        }

        return true;
    }

    /**
     * Search
     * @param $params
     * @return array
     */
    public function search($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $queryString = isset($params['query']) ? $params['query'] : '';

        $mainQuery = new \Elastica\Query;
        $boolQuery = new BoolQuery();
        if (!empty($queryString)) {
            $multiMatchQuery = new MultiMatch();
            $multiMatchQuery->setFields(['title^9', 'store.title^2', 'store.category.name']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery->addMust($multiMatchQuery);
        } else {
            $boolQuery->addMust(new MatchAll());
        }
        $mainQuery->setPostFilter(new Missing('deletedAt'));

        $mainQuery->setQuery($boolQuery);
        $pagination = $this->couponFinder->createPaginatorAdapter($mainQuery);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * get coupon
     *
     * @param $id
     * @return null | Coupon
     */
    public function getCoupon($id)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Term(['id'=> ['value' => $id]]));
        $result = $this->couponFinder->find($query);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * get similar coupon
     *
     * @param Coupon $coupon
     * @return array
     */
    public function getSimilarCoupon(Coupon $coupon)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $boolQuery = new BoolQuery();
        $boolQuery->addMustNot(new Term(['id' => ['value' => $coupon->getId()]]));
        $boolQuery->addMust(new Term(['type' => ['value' => $coupon->getType()]]));
        $pagination = $this->couponFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults(0, 4);
        return $transformedPartialResults->toArray();
    }

    public function getCouponFavorite(AppUser $appUser, $params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $mainQuery = new \Elastica\Query;

        $userQuery = new Term(['likeLists.appUser.id' => $appUser->getId()]);
//        $nestedQuery = new Nested();
//        $nestedQuery->setPath("likeLists");
//        $nestedQuery->setQuery($userQuery);

        $boolQuery = new BoolQuery();
        $boolQuery->addMust($userQuery);

        $mainQuery->setPostFilter(new Missing('deletedAt'));
        $mainQuery->setQuery($boolQuery);

        $pagination = $this->couponFinder->createPaginatorAdapter($mainQuery);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    public function getCouponUsed(AppUser $appUser, $params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $mainQuery = new \Elastica\Query;

        $userQuery = new Term(['useLists.appUser.id' => $appUser->getId()]);
//        $nestedQuery = new Nested();
//        $nestedQuery->setPath("useLists");
//        $nestedQuery->setQuery($userQuery);

        $boolQuery = new BoolQuery();
        $boolQuery->addMust($userQuery);

        $mainQuery->setPostFilter(new Missing('deletedAt'));
        $mainQuery->setQuery($boolQuery);

        $pagination = $this->couponFinder->createPaginatorAdapter($mainQuery);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    public function likeCoupon(AppUser $appUser, Coupon $coupon)
    {
        $likeCoupon = new LikeList();
        $likeCoupon->setCoupon($coupon);
        $likeCoupon->setAppUser($appUser);
        $coupon->addLikeList($likeCoupon);
        return $this->saveCoupon($coupon);
    }

    public function usedCoupon(AppUser $appUser, Coupon $coupon)
    {
        $usedCoupon = new UseList();
        $usedCoupon->setCoupon($coupon);
        $usedCoupon->setAppUser($appUser);
        $usedCoupon->setStatus(0);
        $coupon->addUseList($usedCoupon);
        return $this->saveCoupon($coupon);
    }

    /**
     * List Coupon
     * @param array $params
     *
     * @return array
     */
    public function listCoupon($params ,$categoryId = 0 ,  array $sortArgs)
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
        } 
        $pagination = $this->couponFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results= $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * Filter Coupon By Map
     *
     * @param array $params
     *
     * @return array
     */
    public function filterCouponByMap($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $distance = new GeoDistance(
            'store.location',
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
        $pagination = $this->couponFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results= $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * @param TransformedFinder $couponFinder
     * @return CouponManager
     */
    public function setCouponFinder($couponFinder)
    {
        $this->couponFinder = $couponFinder;
        return $this;
    }

    /**
     * @return TransformedFinder
     */
    public function getCouponFinder()
    {
        return $this->couponFinder;
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
     * @return TransformedFinder
     */
    public function getCategoryFinder()
    {
        return $this->categoryFinder;
    }

}