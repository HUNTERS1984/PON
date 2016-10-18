<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Category;
use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\LikeList;
use CoreBundle\Paginator\Pagination;
use Elastica\Filter\Missing;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Match;
use Elastica\Query\MatchAll;
use Elastica\Query\MultiMatch;
use Elastica\Query\Nested;
use Elastica\Query\Term;
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

    public function getCouponsByCategory(Category $category)
    {
        $conditions = [
            'store.category.id' => [
                'type' => '=',
                'value' => $category->getId()
            ]
        ];

        $conditions['deletedAt'] = [
            'type' => 'is',
            'value' => 'NULL'
        ];

        $orderBy = ['impression' => 'DESC'];

        $query = $this->getQuery($conditions, $orderBy);
        $result = $this->pagination->render($query,0,4);
        var_dump($result);die();

        return $this->pagination->render($query);
    }

    public function getFeaturedCoupon($type, $params)
    {
        switch ($type)
        {
            case 2:
                return $this->getNewestCoupon($params);
            case 3:
                return $this->getNearestCoupon($params);
            case 4:
                return $this->getApprovedCoupon($params);
            default:
                return $this->getPopularCoupon($params);
        }
    }

    public function getApprovedCoupon($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $mainQuery = new \Elastica\Query;



        $pagination = $this->categoryFinder->createPaginatorAdapter($mainQuery);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    public function getNearestCoupon($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $mainQuery = new \Elastica\Query;


        $pagination = $this->categoryFinder->createPaginatorAdapter($mainQuery);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    public function getNewestCoupon($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $mainQuery = new \Elastica\Query;



        $pagination = $this->categoryFinder->createPaginatorAdapter($mainQuery);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    public function getPopularCoupon($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $mainQuery = new \Elastica\Query;


        $mainQuery->addSort(['category.stores.coupons.impression' => ['order' => 'desc']]);


        $pagination = $this->categoryFinder->createPaginatorAdapter($mainQuery);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
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
        $nestedQuery = new Nested();
        $nestedQuery->setPath("likeLists");
        $nestedQuery->setQuery($userQuery);
        $query = new BoolQuery();
        $query
            ->addMust($couponQuery)
            ->addMust($nestedQuery);
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
        $nestedQuery = new Nested();
        $mainQuery = new BoolQuery();
        $mainQuery->addMust($userQuery);
        $mainQuery->addMust($statusQuery);
        $nestedQuery->setPath("useLists");
        $nestedQuery->setQuery($mainQuery);
        $query = new BoolQuery();
        $query
            ->addMust($couponQuery)
            ->addMust($nestedQuery);
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
        $nestedQuery = new Nested();
        $nestedQuery->setPath("likeLists");
        $nestedQuery->setQuery($userQuery);

        $boolQuery = new BoolQuery();
        $boolQuery->addMust($nestedQuery);

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

    /**
     * List Coupon
     * @param array $params
     *
     * @return array
     */
    public function listCoupon($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $conditions = [];
        if (isset($params['title'])) {
            $conditions = [
                'title' => [
                    'type' => 'like',
                    'value' => "%" . $params['title'] . "%"
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