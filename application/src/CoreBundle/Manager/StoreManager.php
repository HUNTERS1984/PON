<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\Store;
use CoreBundle\Paginator\Pagination;
use Doctrine\ORM\Query\Expr;

class StoreManager extends AbstractManager
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
     * @param Store $store
     *
     * @return Store
     */
    public function createStore(Store $store)
    {
        $store->setCreatedAt(new \DateTime());
        $this->saveStore($store);
    }

    /**
     * @param Store $store
     *
     * @return Store
     */
    public function saveStore(Store $store)
    {
        $store->setUpdatedAt(new \DateTime());
        return $this->save($store);
    }

    /**
     * @param Store $store
     *
     * @return boolean
     */
    public function deleteStore(Store $store)
    {
        $store
            ->setDeletedAt(new \DateTime());
        return $this->saveStore($store);
    }

    /**
     * List Store
     * @param array $params , $wheres
     *
     * @return array
     */
    public function listStore($params , $wheres = [] , $orderBys = [])
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $conditions = [];

        if(is_array($wheres)){
            foreach ($wheres as $k=>$v){
                $conditions[$k] = $v;
            }
        }
 
        if(isset($params['name'])) {
            $conditions['name'] = [
                'type' => 'like',
                'value' => "%".$params['name']."%"
            ];
        }


        if(empty($orderBys)){
            $orderBy = ['createdAt' => 'DESC'];
        } else {
            $orderBy = $orderBys;
        }

        $conditions['deletedAt'] = [
            'type' => 'is',
            'value' =>  'NULL'
        ];

        $query = $this->getQuery($conditions, $orderBy, $limit, $offset);

        return $this->pagination->render($query, $limit, $offset);
    }

    /**
     * List Store Join UseList
     * @param array $params
     *
     * @return array
     */
    public function listStoreJoin($params , $wheres = [] , $orderBys = [] , $whereOrder = [])
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $conditions = [];
        if(is_array($wheres)){
            foreach ($wheres as $k=>$v){
                $conditions[$k] = $v;
            }
        }

        if(isset($params['name'])) {
            $conditions['name'] = [
                'type' => 'like',
                'value' => "%".$params['name']."%"
            ];
        }

        if(isset($params['list_store_id'])) {
            $conditions['id'] = [
                'type' => 'in',
                'value' => implode(",", $params['list_store_id'])
            ];
        }


        if(empty($orderBys)){
            $orderBy = ['p.createdAt' => 'DESC'];
        } else {
            $orderBy = $orderBys;
        }

        $orderBy = ['s.usedAt' => 'DESC'];

        $groupBy = "";
        $joinTable['CoreBundle\Entity\UseList']['name'] = 's';
        $joinTable['CoreBundle\Entity\UseList']['type'] = Expr\Join::WITH;
        $joinTable['CoreBundle\Entity\UseList']['where'] = 'p.id = s.store';

        $query = $this->getQueryJoin($conditions, $orderBy, $limit, $offset , '' , $joinTable , $groupBy , $whereOrder);

        return $this->pagination->render($query, $limit, $offset);
    }



}