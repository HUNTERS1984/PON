<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Category;
use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\LikeList;
use CoreBundle\Entity\Store;
use CoreBundle\Entity\UseList;
use CoreBundle\Paginator\Pagination;
use CoreBundle\Utils\StringGenerator;
use Doctrine\ORM\QueryBuilder;
use Elastica\Filter\GeoDistance;
use Elastica\Filter\Missing;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\MatchAll;
use Elastica\Query\MultiMatch;
use Elastica\Query\Nested;
use Elastica\Query\Term;
use Elastica\Query\Range;
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
     * @var TransformedFinder $couponFinder
     */
    protected $useListFinder;

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

    /**
     * @param Coupon $coupon
     *
     * @return Coupon
     */
    public function createCoupon(Coupon $coupon)
    {
        if(!$coupon->getCouponId()) {
            $coupon->setCouponId($this->createID('CO'));
        }
        $coupon
            ->setImpression(0)
            ->setCreatedAt(new \DateTime());
        return $this->saveCoupon($coupon);
    }

    /**
     * @param Coupon $coupon
     *
     * @return Coupon
     */
    public function saveCoupon(Coupon $coupon)
    {
        $hashTags = $this->getHashTags($coupon->getHashTag());
        $coupon->setHashTag($hashTags);
        $coupon->setUpdatedAt(new \DateTime());
        return $this->save($coupon);
    }

    public function getHashTags($hashTags)
    {
        preg_match_all('/#([^\s]+)/', $hashTags, $matches);
        return implode(",", array_map(function($hashTag){
            return strtolower($hashTag);
        }, $matches[0]));
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
        $boolQuery = new BoolQuery();
        $boolQuery
            ->addMust(new Term(['store.category.id' => ['value' => $category->getId()]]))
            ->addMust(new Term(['status' => true]));
        $query->setQuery($boolQuery);
        $query->addSort(['impression' => ['order' => 'desc']]);
        $result = $this->couponFinder->find($query, 4);
        return $result;
    }

    /**
     * getFullPopularCouponsByCategory
     *
     * @param Category $category
     * @param int $offset
     * @param int $limit
     *
     * @return array
     */
    public function getFullPopularCouponsByCategory(Category $category, $offset, $limit)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $boolQuery = new BoolQuery();
        $boolQuery
            ->addMust(new Term(['store.category.id' => ['value' => $category->getId()]]))
            ->addMust(new Term(['status' => true]));
        $query->setQuery($boolQuery);
        $query->addSort(['impression' => ['order' => 'desc']]);

        $pagination = $this->couponFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
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
        $boolQuery
            ->addMust(new Term(['store.category.id' => ['value' => $category->getId()]]))
            ->addMust(new Term(['status' => true]));
        $query->setQuery($boolQuery);
        $query->addSort(['createdAt' => ['order' => 'desc']]);
        $result = $this->couponFinder->find($query, 4);

        return $result;
    }

    /**
     * getFullNewestCouponsByCategory
     *
     * @param Category $category
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getFullNewestCouponsByCategory(Category $category, $offset, $limit)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $boolQuery = new BoolQuery();
        $boolQuery
            ->addMust(new Term(['store.category.id' => ['value' => $category->getId()]]))
            ->addMust(new Term(['status' => true]));
        $query->setQuery($boolQuery);
        $query->addSort(['createdAt' => ['order' => 'desc']]);

        $pagination = $this->couponFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
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
        $mainQuery = new Query\Filtered(new Term(['store.category.id' => ['value' => $category->getId()]]), $distance);
        $boolQuery = new BoolQuery();
        $boolQuery
            ->addMust($mainQuery)
            ->addMust(new Term(['status' => true]));
        $query->setQuery($boolQuery);
        $result = $this->couponFinder->find($query, 4);

        return $result;
    }

    /**
     * getNearestCouponsByCategory
     *
     * @param Category $category
     * @param $latitude
     * @param $longitude
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getFullNearestCouponsByCategory(Category $category, $latitude, $longitude, $offset, $limit)
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
        $mainQuery = new Query\Filtered(new Term(['store.category.id' => ['value' => $category->getId()]]), $distance);
        $boolQuery = new BoolQuery();
        $boolQuery
            ->addMust($mainQuery)
            ->addMust(new Term(['status' => true]));
        $query->setQuery($boolQuery);

        $pagination = $this->couponFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
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
        if (!$user) {
            return [];
        }

        $query = new Query();
        $query->setPostFilter(new Missing('coupon.deletedAt'));

        $userQuery = new Term(['appUser.id' => $user->getId()]);
        $statusQuery = new Term(['status' => 1]);

        $mainQuery = new BoolQuery();
        $mainQuery
            ->addMust($statusQuery)
            ->addMust($userQuery)
            ->addMust(new Term(['coupon.store.category.id' => ['value' => $category->getId()]]));

        $query->setQuery($mainQuery);
        $pagination = $this->useListFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults(0, 4);
        $results = $transformedPartialResults->toArray();
        $coupons = array_map(function(UseList $useList){
            return $useList->getCoupon();
        }, $results);
        return $coupons;
    }

    /**
     * getFullApprovedCouponsByCategory
     *
     * @param Category $category
     * @param AppUser|null $user
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function getFullApprovedCouponsByCategory(Category $category, AppUser $user = null,  $offset, $limit)
    {
        if (!$user) {
            return [];
        }

        $query = new Query();
        $query->setPostFilter(new Missing('coupon.deletedAt'));

        $userQuery = new Term(['appUser.id' => $user->getId()]);
        $statusQuery = new Term(['status' => 1]);

        $mainQuery = new BoolQuery();
        $mainQuery
            ->addMust($statusQuery)
            ->addMust($userQuery)
            ->addMust(new Term(['coupon.store.category.id' => ['value' => $category->getId()]]));

        $query->setQuery($mainQuery);

        $pagination = $this->useListFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $coupons = array_map(function(UseList $useList){
            return $useList->getCoupon();
        }, $results);

        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($coupons, $total, $limit, $offset);
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
        switch ($type) {
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
     * getFullFeaturedCoupon
     *
     * @param $type
     * @param $params
     * @param Category $category
     * @param AppUser|null $user
     * @return array
     */
    public function getFullFeaturedCoupon($type, Category $category, $params, AppUser $user = null)
    {

        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        switch ($type) {
            case 2:
                return $this->getFullNewestCouponsByCategory($category, $offset, $limit);
            case 3:
                return $this->getFullNearestCouponsByCategory($category, $params['latitude'], $params['longitude'], $offset, $limit);
            case 4:
                return $this->getFullApprovedCouponsByCategory($category, $user, $offset, $limit);
            default:
                return $this->getFullPopularCouponsByCategory($category, $offset, $limit);
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
        $categories = $this->getCategories($params);

        foreach ($categories['data'] as $key => $item) {
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
        $latitude = $params['latitude'];
        $longitude = $params['longitude'];

        $categories = $this->getCategories($params);

        foreach ($categories['data'] as $key => $item) {
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
        $categories = $this->getCategories($params);

        foreach ($categories['data'] as $key => $item) {
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
     * @param array $params
     * @return array
     */
    public function getCategories($params)
    {
        return $this->categoryManager->getCategories($params);
    }

    /**
     * getPopularCoupon
     *
     * @param $params
     * @return array
     */
    public function getPopularCoupon($params)
    {
        $categories = $this->getCategories($params);
        foreach ($categories['data'] as $key => $item) {
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
            $multiMatchQuery->setFields(['title^9', 'store.title^2', 'store.category.name', 'store.address']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery->addMust($multiMatchQuery);
        } else {
            $boolQuery->addMust(new MatchAll());
        }
        $boolQuery->addMust(new Term(['status' => true]));
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
        $boolQuery = new BoolQuery();
        $boolQuery
            ->addMust(new Term(['id' => ['value' => $id]]));
        $query->setQuery($boolQuery);
        $result = $this->couponFinder->find($query);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * get coupon
     *
     * @param $couponId
     * @return null | Coupon
     */
    public function getCouponByCouponId($couponId)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Match('couponId', $couponId));
        $result = $this->couponFinder->find($query);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * get coupon
     *
     * @param AppUser $user
     * @param string $code
     *
     * @return null | UseList
     */
    public function getCouponToRequest(AppUser $user, $code)
    {
        $query = new Query();
        $boolQuery = new BoolQuery();
        $date = new \DateTime();
        $boolQuery
            ->addMust(new Term(['appUser.id' => ['value' => $user->getId()]]))
            ->addMust(new Match('code', $code))
            ->addMust(new Term(['status' => ['value' => 1]]))
            ->addMust(new Range('expiredTime',['gte' => $date->format(\DateTime::ISO8601)]));

        $query->setQuery($boolQuery);
        $result = $this->useListFinder->find($query);
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
        $boolQuery
            ->addMust(new Term(['type' => ['value' => $coupon->getType()]]))
            ->addMust(new Term(['status' => true]));
        $pagination = $this->couponFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults(0, 4);
        return $transformedPartialResults->toArray();
    }

    /**
     * List Coupon From Admin
     * @param array $params
     *
     * @return array
     */
    public function listCouponFromAdmin($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['updatedAt' => ['order' => 'desc']]);



        $boolQuery = new BoolQuery();
        if (!empty($queryString)) {
            $multiMatchQuery = new MultiMatch();
            $multiMatchQuery->setFields(['title^9', 'store.title^2', 'store.category.name', 'store.address']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery->addMust($multiMatchQuery);
        } else {
            $boolQuery->addMust(new MatchAll());
        }

        if(isset($params['status']) && in_array($params['status'], ["1","0"])) {
            $status = $params["status"] == 1;
            $boolQuery->addMust(new Term(['status' => ['value' => $status]]));
        }

        $query->setQuery($boolQuery);

        $pagination = $this->couponFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * List Coupon From Client
     * @param array $params
     * @param AppUser $user
     *
     * @return array
     */
    public function listCouponFromClient($params, AppUser $user)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['updatedAt' => ['order' => 'desc']]);



        $boolQuery = new BoolQuery();
        if (!empty($queryString)) {
            $multiMatchQuery = new MultiMatch();
            $multiMatchQuery->setFields(['title^9', 'store.title^2', 'store.category.name', 'store.address']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery->addMust($multiMatchQuery);
        } else {
            $boolQuery->addMust(new MatchAll());
        }

        if(isset($params['status']) && in_array($params['status'], ["1","0"])) {
            $status = $params["status"] == 1;
            $boolQuery->addMust(new Term(['status' => ['value' => $status]]));
        }

        $boolQuery->addMust(new Term(['store.id' => $user->getStore()->getId()]));

        $query->setQuery($boolQuery);

        $pagination = $this->couponFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * List Request Coupon
     * @param array $params
     *
     * @return array
     */
    public function listRequestCoupons($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $query = new Query();
        $query->addSort(['createdAt' => ['order' => 'asc']]);


        $boolQuery = new BoolQuery();
        $boolQuery->addMust(new Term(['status' => ['value' => 2]]));
        $query->setQuery($boolQuery);

        $pagination = $this->useListFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    public function findCouponByHashTag2($hashTags)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $date = new \DateTime();
        $date->setTime(23,59,59);
        $queryString = new \Elastica\Query\QueryString();
        $queryString->setQuery("#store1 #abc");
        $queryString->setAnalyzer('hashtag');
        $queryString->setFields(array('hashTag'));

        $boolQuery = new BoolQuery();
        $boolQuery
            ->addMust(new Term(['status' => ['value' => 1]]))
            ->addMust(new Query\Range('expiredTime',['gte' => $date->format(\DateTime::ISO8601)]))
            ->addMust($queryString);
        $query
            ->setQuery($boolQuery);
        $pagination = $this->couponFinder->createPaginatorAdapter($query);

        $transformedPartialResults = $pagination->getResults(0, 500);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, 500, 0);
    }

    public function findCouponByHashTag(array $hashTags)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $date = new \DateTime();
        $date->setTime(23,59,59);
        $boolQuery = new BoolQuery();
        $boolQuery
            ->addMust(new Term(['status' => ['value' => 1]]))
            ->addMust(new Query\Range('expiredTime',['gte' => $date->format(\DateTime::ISO8601)]))
            ->addMust(new Query\Terms('hashTag', $hashTags));
        $query->setQuery($boolQuery);

        $pagination = $this->couponFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults(0, 500);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, 500, 0);
    }

    /**
     * List Request Coupon
     * @param string $code
     *
     * @return array
     */
    public function getRequestCouponDetail($code)
    {

        $query = new Query();
        $boolQuery = new BoolQuery();
        $boolQuery->addMust(new Term(['status' => ['value' => 2]]));
        $boolQuery->addMust(new Match('code', $code));
        $query->setQuery($boolQuery);
        $result = $this->useListFinder->find($query);
        return !empty($result) ? $result[0] : null;
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
     * @param TransformedFinder $useListFinder
     * @return CouponManager
     */
    public function setUseListFinder($useListFinder)
    {
        $this->useListFinder = $useListFinder;
        return $this;
    }

    /**
     * @return TransformedFinder
     */
    public function getUseListFinder()
    {
        return $this->useListFinder;
    }

}