<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\FollowList;
use CoreBundle\Entity\Store;
use CoreBundle\Paginator\Pagination;
use Elastica\Filter\Missing;
use Elastica\Query\BoolQuery;
use Elastica\Query\Term;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class FollowListManager extends AbstractManager
{
    /**
     * @var TransformedFinder $couponFinder
     */
    protected $followListFinder;

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


    public function getFollowShops(AppUser $appUser, $params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) && $params['page_index'] > 0 ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $mainQuery = new \Elastica\Query;

        $query = new Term(['appUser.id' => $appUser->getId()]);

        $mainQuery->setPostFilter(new Missing('store.deletedAt'));
        $mainQuery->setQuery($query);

        $pagination = $this->followListFinder->createPaginatorAdapter($mainQuery);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $stores = array_map(function(FollowList $followList){
            return $followList->getStore();
        }, $results);
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($stores, $total, $limit, $offset);
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
        $storeQuery = new Term(['store.id'=> $store->getId()]);
        $userQuery = new Term(['appUser.id'=> $user->getId()]);

        $query = new BoolQuery();
        $query
            ->addMust($storeQuery)
            ->addMust($userQuery);
        $result = $this->followListFinder->find($query);

        return !empty($result) ? $result[0] : null;
    }

    public function unFollowStore(FollowList $followList)
    {
        return $this->delete($followList);
    }

    /**
     * @param FollowList $followList
     *
     * @return FollowList
     */
    public function saveFollowList(FollowList $followList)
    {
        return $this->save($followList);
    }

    public function followStore(AppUser $appUser, Store $store)
    {
        $followStore = new FollowList();
        $followStore->setStore($store);
        $followStore->setAppUser($appUser);
        return $this->saveFollowList($followStore);
    }

    /**
     * @param TransformedFinder $followListFinder
     * @return UseListManager
     */
    public function setFollowListFinder($followListFinder)
    {
        $this->followListFinder = $followListFinder;
        return $this;
    }

}