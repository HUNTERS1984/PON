<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\Coupon;
use CoreBundle\Paginator\Pagination;

class CouponManager extends AbstractManager
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

        if(isset($params['store_id'])) {
            $conditions = [
                'store' => [
                    'type' => '=',
                    'value' => $params['store_id']
                ]
            ];
        }

        $conditions['deletedAt'] = [
            'type' => 'is',
            'value' =>  'NULL'
        ];

        $orderBy = ['createdAt' => 'DESC'];

        $query = $this->getQuery($conditions, $orderBy, $limit, $offset);
        //return $query;
        return $this->pagination->render($query, $limit, $offset);
    }

}