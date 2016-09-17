<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\CouponType;
use CoreBundle\Paginator\Pagination;

class CouponTypeManager extends AbstractManager
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
     * @param CouponType $couponType
     *
     * @return CouponType
     */
    public function saveCouponType(CouponType $couponType)
    {
        $couponType->setUpdatedAt(new \DateTime());
        return $this->save($couponType);
    }

    /**
     * @param CouponType $couponType
     *
     * @return CouponType
     */
    public function createCouponType(CouponType $couponType)
    {
        $couponType->setCreatedAt(new \DateTime());
        $this->saveCouponType($couponType);
    }

    /**
     * @param CouponType $couponType
     *
     * @return boolean
     */
    public function deleteCouponType(CouponType $couponType)
    {
        $couponType
            ->setDeletedAt(new \DateTime());
        return $this->saveCouponType($couponType);
    }

    /**
     * List Coupon Type
     * @param array $params
     *
     * @return array
     */
    public function listCouponType($params)
    {
        $limit = isset($params['limit']) ? $params['limit'] : 10;
        $offset = isset($params['offset']) ? $params['offset'] : 0;

        $conditions = [];
        if(isset($params['name'])) {
            $conditions = [
                'name' => [
                    'type' => 'like',
                    'value' => "%".$params['name']."%"
                ]
            ];
        }

        $conditions['deletedAt'] = [
            'type' => 'is',
            'value' =>  'NULL'
        ];

        $orderBy = ['createdAt' => 'DESC'];

        $query = $this->getQuery($conditions, $orderBy, $limit, $offset);

        return $this->pagination->render($query, $limit, $offset);
    }

}