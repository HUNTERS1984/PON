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

        if(isset($params['app_user_id'])) {
            $conditions['appUser'] = [
                'type' => '=',
                'value' => $params['app_user_id']
            ];
        }

        $orderBy = ['id' => 'DESC'];
        $query = $this->getQuery($conditions, $orderBy, $limit, $offset);
        return $this->pagination->render($query, $limit, $offset);
    }

    /**
     * List Id Shop User Follow
     * @param int $app_user_id
     *
     * @return array
     */
    public function listAllOfUser($app_user_id)
    {
        $limit = 10000;
        $offset = 0;

        $conditions = [];

        $conditions['appUser'] = [
            'type' => '=',
            'value' => $app_user_id
        ];

        $orderBy = ['id' => 'DESC'];
        $query = $this->getQuery($conditions, $orderBy, $limit, $offset);
        $listStores = $this->pagination->render($query, $limit, $offset);

        $arrIdResult = [];
        foreach ($listStores['data'] as $k=>$v){
            $arrIdResult[] = $v->getStore()->getId();
        }
        return $arrIdResult;
    }

}