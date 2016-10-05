<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\Category;
use CoreBundle\Paginator\Pagination;
use Doctrine\ORM\Query\Expr;

class CategoryManager extends AbstractManager
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
     * @param Category $category
     *
     * @return Category
     */
    public function saveCategory(Category $category)
    {
        $category->setUpdatedAt(new \DateTime());
        return $this->save($category);
    }

    /**
     * @param Category $category
     *
     * @return Category
     */
    public function createCouponType(Category $category)
    {
        $category->setCreatedAt(new \DateTime());
        $this->saveCategory($category);
    }

    /**
     * @param Category $category
     *
     * @return boolean
     */
    public function deleteCouponType(Category $category)
    {
        $category
            ->setDeletedAt(new \DateTime());
        return $this->saveCategory($category);
    }

    /**
     * List Category
     * @param array $params
     *
     * @return array
     */
    public function listCategory($params)
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

    /**
     * List Category Count Shop Follow
     * @param array $params
     *
     * @return array
     */
    public function listCategoryCountShop($params)
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


        $select = 'p, COUNT(s.id) AS shop_count';
        $groupBy = "s.category";
        $joinTable['CoreBundle\Entity\Store']['name'] = 's';
        $joinTable['CoreBundle\Entity\Store']['type'] = Expr\Join::WITH;
        $joinTable['CoreBundle\Entity\Store']['where'] = 'p.id = s.category';

        $query = $this->getQueryJoin($conditions, $orderBy, $limit, $offset , $select , $joinTable , $groupBy);

        return $this->pagination->render($query, $limit, $offset);
    }

}