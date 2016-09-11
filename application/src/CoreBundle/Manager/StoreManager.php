<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\Store;
use CoreBundle\Paginator\Pagination;

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
     * @param array $params
     *
     * @return array
     */
    public function listStore($params)
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