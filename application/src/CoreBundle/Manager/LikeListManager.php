<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\LikeList;
use CoreBundle\Paginator\Pagination;

class LikeListManager extends AbstractManager
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
     * @param LikeList $likeList
     *
     * @return LikeList
     */
    public function createLikeList(LikeList $likeList)
    {
        return $this->save($likeList);
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

        if(isset($params['app_user_id'])) {
            $conditions['appUser'] = [
                'type' => '=',
                'value' => $params['app_user_id']
            ];
        }

        if(isset($params['list_coupon_id'])) {
            $conditions['coupon'] = [
                'type' => 'in',
                'value' => implode(",", $params['list_coupon_id'])
            ];
        }

        $orderBy = ['id' => 'DESC'];

        $query = $this->getQuery($conditions, $orderBy, $limit, $offset);
        return $this->pagination->render($query, $limit, $offset);
    }


    /**
     * List Id Coupon User Liked
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
        $listCoupons = $this->pagination->render($query, $limit, $offset);

        $arrIdResult = [];
        foreach ($listCoupons['data'] as $k=>$v){
            $arrIdResult[] = $v->getCoupon()->getId();
        }
        return $arrIdResult;
    }

}