<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\UseList;
use CoreBundle\Paginator\Pagination;
use Elastica\Filter\Missing;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class UseListManager extends AbstractManager
{
    /**
     * @var TransformedFinder $couponFinder
     */
    protected $useListFinder;

    /**
     * @var Pagination
     */
    protected $pagination;

    /**
     * @var Pagination $pagination
     */
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * @param UseList $useList
     *
     * @return UseList
     */
    public function createUseList(UseList $useList)
    {
        $useList->setCreatedAt(new \DateTime());
        $this->saveUseList($useList);
    }

    /**
     * @param UseList $useList
     *
     * @return UseList
     */
    public function saveUseList(UseList $useList)
    {
        $useList->setUpdatedAt(new \DateTime());
        return $this->save($useList);
    }


    /**
     * get QR Code
     *
     * @param AppUser $user
     * @param Coupon $coupon
     * @return string|null
     */
    public function getCode(AppUser $user = null, Coupon $coupon)
    {
        if (!$user) {
            return null;
        }
        $couponQuery = new Term(['coupon.id' => $coupon->getId()]);
        $userQuery = new Term(['appUser.id' => $user->getId()]);
        $statusQuery = new Term(['status' => 1]);
        $query = new BoolQuery();
        $query
            ->addMust($couponQuery)
            ->addMust($userQuery)
            ->addMust($statusQuery);
        $result = $this->useListFinder->find($query);

        return !empty($result) ? $result[0]->getCode() : null;
    }

    public function getUsedCoupons(AppUser $appUser, $params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $mainQuery = new \Elastica\Query;

        $userQuery = new Term(['appUser.id' => $appUser->getId()]);
        $statusQuery = new Term(['status' => 3]);

        $boolQuery = new BoolQuery();
        $boolQuery
            ->addMust($userQuery)
            ->addMust($statusQuery);

        $mainQuery->setPostFilter(new Missing('coupon.deletedAt'));
        $mainQuery->setQuery($boolQuery);

        $pagination = $this->useListFinder->createPaginatorAdapter($mainQuery);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $coupons = array_map(function(UseList $useList){
            return $useList->getCoupon();
        }, $results);

        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($coupons, $total, $limit, $offset);
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
        if (!$coupon->isNeedLogin()) {
            return true;
        }

        if (!$user) {
            return false;
        }
        $couponQuery = new Term(['coupon.id' => $coupon->getId()]);
        $userQuery = new Term(['appUser.id' => $user->getId()]);
        $statusQuery = new Term(['status' => 1]);
        $query = new BoolQuery();
        $query
            ->addMust($couponQuery)
            ->addMust($userQuery)
            ->addMust($statusQuery);
        $result = $this->useListFinder->find($query);
        return !empty($result);
    }


    /**
     * Accept Coupon
     * @param UseList $useList
     *
     * @return array
     */
    public function acceptCoupon(UseList $useList)
    {
        $useList->setStatus(3);
        return $this->saveUseList($useList);
    }

    /**
     * Decline Coupon
     * @param UseList $useList
     *
     * @return array
     */
    public function declineCoupon(UseList $useList)
    {
        $useList->setStatus(4);
        return $this->saveUseList($useList);
    }

    /**
     * @param TransformedFinder $useListFinder
     * @return UseListManager
     */
    public function setUseListFinder($useListFinder)
    {
        $this->useListFinder = $useListFinder;
        return $this;
    }

}