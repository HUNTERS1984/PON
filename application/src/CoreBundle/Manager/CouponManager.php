<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\Coupon;
use CoreBundle\Paginator\Pagination;
use Doctrine\ORM\Query\Expr;

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
    public function listCoupon($params , $wheres = [] , $orderBys = [])
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $conditions = [];

		if(is_array($wheres)){
            foreach ($wheres as $k=>$v){
                $conditions[$k] = $v;
            }
        }
        
        if(isset($params['title'])) {
            $conditions['title'] = [
                'type' => 'like',
                'value' => "%".$params['title']."%"
            ];
        }

        if(isset($params['type'])) {
            $conditions['type'] = [
                'type' => '=',
                'value' => $params['type']
            ];
        }

        if(isset($params['store_id'])) {
            $conditions['store'] = [
                'type' => '=',
                'value' => $params['store_id']
            ];
        }

        $conditions['deletedAt'] = [
            'type' => 'is',
            'value' =>  'NULL'
        ];

		
        if(empty($orderBys)){
            $orderBy = ['createdAt' => 'DESC'];
        } else {
            $orderBy = $orderBys;
        }
        
        $query = $this->getQuery($conditions, $orderBy, $limit, $offset);
        //return $query;
        return $this->pagination->render($query, $limit, $offset);
    }

    /**
     * List Coupon Join UseList
     * @param array $params
     *
     * @return array
     */
    public function listCouponJoin($params , $wheres = [] , $orderBys = [] , $whereOrder = [])
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $conditions = [];
        if(is_array($wheres)){
            foreach ($wheres as $k=>$v){
                $conditions[$k] = $v;
            }
        }

        if(isset($params['title'])) {
            $conditions['title'] = [
                'type' => 'like',
                'value' => "%".$params['name']."%"
            ];
        }

        $conditions['deletedAt'] = [
            'type' => 'is',
            'value' =>  'NULL'
        ];

        if(empty($orderBys)){
            $orderBy = ['p.createdAt' => 'DESC'];
        } else {
            $orderBy = $orderBys;
        }

        $orderBy = ['s.usedAt' => 'DESC'];

        $groupBy = "";
        $joinTable['CoreBundle\Entity\UseList']['name'] = 's';
        $joinTable['CoreBundle\Entity\UseList']['type'] = Expr\Join::WITH;
        $joinTable['CoreBundle\Entity\UseList']['where'] = 'p.id = s.coupon';

        $query = $this->getQueryJoin($conditions, $orderBy, $limit, $offset , '' , $joinTable , $groupBy , $whereOrder);

        return $this->pagination->render($query, $limit, $offset);
    }
}