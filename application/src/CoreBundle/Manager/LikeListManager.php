<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\LikeList;
use CoreBundle\Event\LikeListEvents;
use CoreBundle\Paginator\Pagination;
use Elastica\Filter\Missing;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LikeListManager extends AbstractManager
{
    /**
     * @var TransformedFinder $couponFinder
     */
    protected $likeListFinder;

    /**
     * @var Pagination
     */
    protected $pagination;

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
     * @param LikeList $likeList
     *
     * @return LikeList
     */
    public function createLikeList(LikeList $likeList)
    {
        $this->saveLikeList($likeList);
    }

    /**
     * @param LikeList $likeList
     *
     * @return LikeList
     */
    public function saveLikeList(LikeList $likeList)
    {
        return $this->save($likeList);
    }

    public function likeCoupon(AppUser $appUser, Coupon $coupon)
    {
        $likeCoupon = new LikeList();
        $likeCoupon->setCoupon($coupon);
        $likeCoupon->setAppUser($appUser);

        $likeListEvents = new LikeListEvents();
        $likeListEvents->setLikeList($likeCoupon);
        $this->dispatcher->dispatch(LikeListEvents::PRE_CREATE, $likeListEvents);
        $likeCoupon = $this->saveLikeList($likeCoupon);
        $this->dispatcher->dispatch(LikeListEvents::POST_CREATE, $likeListEvents);

        return $likeCoupon;
    }

    public function unLikeCoupon(LikeList $likeList)
    {
        return $this->delete($likeList);
    }

    /**
     * is like
     *
     * @param AppUser $user
     * @param Coupon $coupon
     * @return LikeList
     */
    public function isLike(AppUser $user = null, Coupon $coupon)
    {
        if (!$user) {
            return null;
        }
        $couponQuery = new Term(['coupon.id' => $coupon->getId()]);
        $userQuery = new Term(['appUser.id' => $user->getId()]);
        $query = new BoolQuery();
        $query
            ->addMust($couponQuery)
            ->addMust($userQuery);
        $result = $this->likeListFinder->find($query);
        return !empty($result) ? $result[0] : null;
    }

    public function getFavoriteCoupons(AppUser $appUser, $params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $mainQuery = new \Elastica\Query;

        $userQuery = new Term(['appUser.id' => $appUser->getId()]);
        $mainQuery->setPostFilter(new Missing('coupon.deletedAt'));
        $mainQuery->setQuery($userQuery);

        $pagination = $this->likeListFinder->createPaginatorAdapter($mainQuery);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $coupons = array_map(function(LikeList $likeList){
            return $likeList->getCoupon();
        }, $results);

        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($coupons, $total, $limit, $offset);
    }

    /**
     * @param TransformedFinder $likeListFinder
     * @return LikeListManager
     */
    public function setLikeListFinder($likeListFinder)
    {
        $this->likeListFinder = $likeListFinder;
        return $this;
    }

    /**
     * @param EventDispatcherInterface $dispatcher
     * @return LikeListManager
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher): LikeListManager
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

}