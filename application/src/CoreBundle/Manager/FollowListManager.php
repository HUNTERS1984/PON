<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\FollowList;
use CoreBundle\Paginator\Pagination;

class FollowListManager extends AbstractManager
{
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
     * @param FollowList $followList
     *
     * @return FollowList
     */
    public function saveFollowList(FollowList $followList)
    {
        return $this->save($followList);
    }


    /**
     * List Follow Shop
     * @param array $params
     *
     * @return array
     */
    public function listShop($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $conditions = [];

        $query = $this->getQuery($conditions, $orderBy, $limit, $offset);

        return $this->pagination->render($query, $limit, $offset);
    }

}