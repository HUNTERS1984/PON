<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\UseList;
use CoreBundle\Paginator\Pagination;
use Elastica\Query;
use Elastica\Filter\Missing;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use Elastica\Query\Terms;
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
        $useList
            ->setCreatedAt(new \DateTime())
            ->setCode($this->createID('UL'));
        return $this->saveUseList($useList);
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

    /**
     * @param UseList $useList
     *
     * @return boolean
     */
    public function requestCoupon(UseList $useList)
    {
        $useList
            ->setStatus(2)
            ->setRequestedAt(new \DateTime());
        return $this->saveUseList($useList);
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
     * getUseCoupon
     *
     * @param AppUser $appUser
     * @param Coupon $coupon
     * @return UseList|null
     */
    public function getUseCoupon(AppUser $appUser, Coupon $coupon)
    {
        $mainQuery = new \Elastica\Query;

        $userQuery = new Term(['appUser.id' => $appUser->getId()]);
        $couponQuery = new Term(['coupon.id' => $coupon->getId()]);

        $boolQuery = new BoolQuery();
        $boolQuery
            ->addMust($userQuery)
            ->addMust($couponQuery);

        $mainQuery->setPostFilter(new Missing('coupon.deletedAt'));
        $mainQuery->setQuery($boolQuery);

        $result = $this->useListFinder->find($mainQuery);

        return !empty($result) ? $result[0] : null;
    }

    /**
     * getUseCoupon By Id
     *
     * @param integer $id
     * @return UseList|null
     */
    public function getUseCouponById($id)
    {
        $mainQuery = new \Elastica\Query;

        $mainQuery->setPostFilter(new Missing('coupon.deletedAt'));
        $mainQuery->setQuery(new Term(['id' => $id]));

        $result = $this->useListFinder->find($mainQuery);

        return !empty($result) ? $result[0] : null;
    }

    /**
     * create new approve coupon
     *
     * @param AppUser $appUser
     * @param Coupon $coupon
     * @return UseList|null
     */
    public function createNewUseList(AppUser $appUser, Coupon $coupon)
    {
        $useList = new UseList();
        $useList
            ->setAppUser($appUser)
            ->setCoupon($coupon)
            ->setStatus(0)
            ->setExpiredTime($coupon->getExpiredTime());

        return $this->createUseList($useList);
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
        if (!$user) {
            return false;
        }

        $query = new Query();
        $query->setPostFilter(new Missing('coupon.deletedAt'));

        $couponQuery = new Term(['coupon.id' => $coupon->getId()]);
        $userQuery = new Term(['appUser.id' => $user->getId()]);
        $statusQuery = new Term(['status' => 1]);
        $mainQuery = new BoolQuery();
        $mainQuery
            ->addMust($couponQuery)
            ->addMust($userQuery)
            ->addMust($statusQuery);

        $query->setQuery($mainQuery);
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
     * get used number
     *
     * @param AppUser $user
     * @return integer
     */
    public function getUsedNumber(AppUser $user)
    {
        $userQuery = new Term(['appUser.id'=> $user->getId()]);
        $statusQuery = new Term(['status' => 3]);

        $query = new Query();
        $mainQuery = new BoolQuery();
        $mainQuery
            ->addMust($userQuery)
            ->addMust($statusQuery);
        $query->setQuery($mainQuery);
        $pagination = $this->useListFinder->createPaginatorAdapter($query);
        return $pagination->getTotalHits();
    }

    /**
     * Approve Coupon
     * @param UseList $useList
     *
     * @return array
     */
    public function approveCoupon(UseList $useList)
    {
        $useList->setStatus(1);
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

    public function getUseListManagerFromAdmin($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['createdAt' => ['order' => 'desc']]);

        $statusQuery = new BoolQuery();

        if(isset($params['status']) && in_array($params['status'], ["1","0"])) {
            $status = (int)$params["status"];
            $statusQuery->addShould(new Query\Term(['status' => $status]));
        } else {
            $statusQuery
                ->addShould(new Query\Term(['status'=> 0]))
                ->addShould(new Query\Term(['status'=> 1]));
        }

        $boolQuery = new Query\BoolQuery();

        if (!empty($queryString)) {
            $multiMatchQuery = new Query\MultiMatch();
            $multiMatchQuery->setFields(['appUser.username','coupon.hashTag', 'status']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery
                ->addMust($statusQuery)
                ->addMust($multiMatchQuery);

        } else {
            $boolQuery->addMust($statusQuery);
        }

        $query->setQuery($boolQuery);

        $pagination = $this->useListFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    public function getUseListManagerFromClient($params, AppUser $user)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'desc';
        $sortBy = 'updatedAt';
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort([$sortBy => ['order' => $orderBy]]);

        $statusQuery = new BoolQuery();
        if(isset($params['status']) && in_array($params['status'], ["1","0"])) {
            $status = (int)$params["status"];
            $statusQuery->addShould(new Query\Term(['status' => $status]));
        } else {
            $statusQuery
                ->addShould(new Query\Term(['status'=> 0]))
                ->addShould(new Query\Term(['status'=> 1]));
        }

        $boolQuery = new Query\BoolQuery();
        if (!empty($queryString)) {
            $multiMatchQuery = new Query\MultiMatch();
            $multiMatchQuery->setFields(['appUser.username','coupon.hashTag', 'status']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery
                ->addMust($statusQuery)
                ->addMust($multiMatchQuery);
        } else {
            $boolQuery
                ->addMust($statusQuery);
        }

        $boolQuery->addMust(new Query\Term(['coupon.store.id'=> $user->getStore()->getId()]));
        $query->setQuery($boolQuery);

        $pagination = $this->useListFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

}