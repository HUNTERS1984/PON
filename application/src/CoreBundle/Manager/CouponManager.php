<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Coupon;
use CoreBundle\Paginator\Pagination;
use Elastica\Query\BoolQuery;
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
    public function isLike(AppUser $user, Coupon $coupon)
    {
        $couponQuery = new Term(['id'=> $coupon->getId()]);
        $userQuery = new Term(['likeLists.appUser.id'=> $user->getId()]);
        $nestedQuery = new Nested();
        $nestedQuery->setPath("likeLists");
        $nestedQuery->setQuery($userQuery);
        $query = new BoolQuery();
        $query
            ->addMust($couponQuery)
            ->addMust($nestedQuery);
        $store = $this->couponFinder->find($query);
        if(!$store) {
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
    public function isCanUse(AppUser $user, Coupon $coupon)
    {
        $couponQuery = new Term(['id'=> $coupon->getId()]);
        $userQuery = new Term(['useLists.appUser.id'=> $user->getId()]);
        $statusQuery = new Term(['useLists.status'=> 1]);
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
        if(!$store) {
            return false;
        }

        return true;
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
        if(isset($params['title'])) {
            $conditions = [
                'title' => [
                    'type' => 'like',
                    'value' => "%".$params['title']."%"
                ]
            ];
        }

        $conditions['deletedAt'] = [
            'type' => 'is',
            'value' =>  'NULL'
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

}