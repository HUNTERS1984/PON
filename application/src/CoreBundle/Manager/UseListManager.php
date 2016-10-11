<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\UseList;
use CoreBundle\Paginator\Pagination;

class UseListManager extends AbstractManager
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
     * @param UseList $useList
     *
     * @return UseList
     */
    public function createUseList(UseList $useList)
    {
        return $this->save($useList);
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
        $orderBy = [];

        $query = $this->getQuery($conditions, $orderBy, $limit, $offset);

        return $this->pagination->render($query, $limit, $offset);
    }


    /**
     * List Id Coupon User Can Use
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
        $conditions['status'] = [
            'type' => '=',
            'value' => 1
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