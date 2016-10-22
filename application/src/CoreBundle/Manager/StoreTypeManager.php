<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\StoreType;
use CoreBundle\Paginator\Pagination;
use Doctrine\DBAL\Query\QueryBuilder;

class StoreTypeManager extends AbstractManager
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
     * @param StoreType $storeType
     *
     * @return StoreType
     */
    public function saveStoreType(StoreType $storeType)
    {
        $storeType->setUpdatedAt(new \DateTime());
        return $this->save($storeType);
    }

    /**
     * @param StoreType $storeType
     *
     * @return StoreType
     */
    public function createStoreType(StoreType $storeType)
    {
        $storeType->setCreatedAt(new \DateTime());
        $this->saveStoreType($storeType);
    }

    /**
     * @param StoreType $storeType
     *
     * @return boolean
     */
    public function deleteStoreType(StoreType $storeType)
    {
        $storeType
            ->setDeletedAt(new \DateTime());
        return $this->saveStoreType($storeType);
    }

    /**
     * List Store Type
     * @param array $params
     *
     * @return array
     */
    public function listStoreType($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

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