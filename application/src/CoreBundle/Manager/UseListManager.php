<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\UseList;
use CoreBundle\Event\UseListEvents;
use CoreBundle\Paginator\Pagination;
use Elastica\Query;
use Elastica\Filter\Missing;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use Elastica\Query\Terms;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Predis\Client;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

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
     * @var Client $redisManager
    */
    protected $redisManager;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

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
        if(!$useList->getCode()) {
            $useList->setCode($this->createID('UL'));
        }
        $useList
            ->setCreatedAt(new \DateTime());
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
        $useListEvents = new UseListEvents();
        $useListEvents->setUseList($useList);
        $this->dispatcher->dispatch(UseListEvents::PRE_CREATE, $useListEvents);
        $useList = $this->save($useList);
        $this->dispatcher->dispatch(UseListEvents::POST_CREATE, $useListEvents);

        return $useList;
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

        if($coupon->getType() == 2) {
            if($coupon->getDeletedAt() || !$coupon->getStatus()) {
                return false;
            }
            $query = new Query();
            $mainQuery = new BoolQuery();
            $mainQuery
                ->addMust($couponQuery)
                ->addMust($userQuery);
            $query->setQuery($mainQuery);
            $result = $this->useListFinder->find($query);
            if(empty($result)) {
                $key = sprintf("p:ul:%s.%s",$user->getId(), $coupon->getId());
                $code = $this->redisManager->hget($key, 'code');
                $expiredTime = $coupon->getExpiredTime();
                $expiredTime->modify('+1 day');
                $expireAt = $expiredTime->getTimestamp();
                if(!empty($code)) {
                    $keyCode = sprintf("p:code:%s", $code);
                    $this->redisManager->expireat($key, $expireAt);
                    $this->redisManager->expireat($keyCode, $expireAt);
                    return $code;
                }

                $code = $this->createID('UL');
                $keyCode = sprintf("p:code:%s", $code);
                $this->redisManager->hset($key, 'code', $code);
                $this->redisManager->hset($keyCode, 'user', $user->getId());
                $this->redisManager->hset($keyCode, 'coupon', $coupon->getId());
                $this->redisManager->expireat($key, $expireAt);
                $this->redisManager->expireat($keyCode, $expireAt);
                return $code;
            }
        }

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

    public function findUseCoupon(AppUser $appUser, Coupon $coupon)
    {
        /** @var UseList $useList */
        $useList = $this->findOneBy(['appUser' => $appUser, 'coupon' => $coupon]);

        return !$useList || $useList->getCoupon()->getDeletedAt() ? null : $useList;
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
     * @param int $status
     * @return UseList|null
     */
    public function createNewUseList(AppUser $appUser, Coupon $coupon, $status = 0)
    {
        $useList = new UseList();
        $useList
            ->setAppUser($appUser)
            ->setCoupon($coupon)
            ->setStatus($status)
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

        $couponQuery = new Term(['coupon.id' => $coupon->getId()]);
        $userQuery = new Term(['appUser.id' => $user->getId()]);
        if($coupon->getType() == 2) {
            if($coupon->getDeletedAt() || !$coupon->getStatus()) {
                return false;
            }
            $query = new Query();
            $mainQuery = new BoolQuery();
            $mainQuery
                ->addMust($couponQuery)
                ->addMust($userQuery);
            $query->setQuery($mainQuery);
            $result = $this->useListFinder->find($query);
            if(empty($result)) {
                return true;
            }
        }

        $query = new Query();
        $query->setPostFilter(new Missing('coupon.deletedAt'));
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

    public function approveAllCouponFromAdmin($ids = [])
    {
        $pageIndex = 1;
        while(true) {
            $data = $this->getUseListToApproveFromAdmin(['page_index' => $pageIndex], $ids);
            $pageIndex = $data['pagination']['current_page'];
            $this->approveListCoupon($data['data']);
            if($pageIndex >= $data['pagination']['page_total']) {
                break;
            }
        }
    }

    public function approveAllCouponFromClient(AppUser $user, $ids = [])
    {
        $pageIndex = 1;
        while(true) {
            $data = $this->approveAllCouponFromClient(['page_index' => $pageIndex], $user, $ids);
            $pageIndex = $data['pagination']['current_page'];
            $this->approveListCoupon($data['data']);
            if($pageIndex >= $data['pagination']['page_total']) {
                break;
            }
        }
    }

    public function approveListCoupon($list)
    {
        foreach($list as $item)
        {
            $this->approveCoupon($item);
        }
    }

    public function getUseListToApproveFromClient($params, AppUser $user, $ids = [])
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 200;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'desc';
        $sortBy = 'updatedAt';
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort([$sortBy => ['order' => $orderBy]]);

        $statusQuery = new BoolQuery();
        $statusQuery
            ->addShould(new Query\Term(['status'=> 0]));

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

        if(!empty($ids)) {
            $boolQuery->addMust(new Terms('id', $ids));
        }

        $boolQuery->addMust(new Query\Term(['coupon.store.id'=> $user->getStore()->getId()]));
        $query->setQuery($boolQuery);

        $pagination = $this->useListFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }


    public function getUseListToApproveFromAdmin($params, $ids = [])
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 200;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['createdAt' => ['order' => 'desc']]);

        $statusQuery = new BoolQuery();
        $statusQuery
            ->addShould(new Query\Term(['status'=> 0]));

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

        if(!empty($ids)) {
            $boolQuery->addMust(new Terms('id', $ids));
        }

        $query->setQuery($boolQuery);

        $pagination = $this->useListFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
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
     * UnApprove Coupon
     * @param UseList $useList
     *
     * @return array
     */
    public function unApproveCoupon(UseList $useList)
    {
        $useList->setStatus(0);
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
        $queryString = isset($params['query']) ? str_replace('#', '', $params['query']) : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['updatedAt' => ['order' => 'desc']]);

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

        $date = new \DateTime();
        $expiredTimeQuery = new Query\Range('expiredTime',['gte' => $date->format(\DateTime::ISO8601)]);
        $boolQuery->addMust($expiredTimeQuery);

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
        $queryString = isset($params['query']) ? str_replace('#', '', $params['query']) : '';

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

        $date = new \DateTime();
        $expiredTimeQuery = new Query\Range('expiredTime',['gte' => $date->format(\DateTime::ISO8601)]);
        $boolQuery->addMust($expiredTimeQuery);

        $boolQuery->addMust(new Query\Term(['coupon.store.id'=> $user->getStore()->getId()]));
        $query->setQuery($boolQuery);

        $pagination = $this->useListFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * @param Client $redisManager
     * @return UseListManager
     */
    public function setRedisManager($redisManager)
    {
        $this->redisManager = $redisManager;

        return $this;
    }

    /**
     * @param EventDispatcherInterface $dispatcher
     * @return UseListManager
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher): UseListManager
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

}