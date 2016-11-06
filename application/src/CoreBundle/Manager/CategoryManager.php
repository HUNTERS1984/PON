<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\Category;
use CoreBundle\Paginator\Pagination;
use Elastica\Aggregation\Nested;
use Elastica\Aggregation\ValueCount;
use Elastica\Filter\Missing;
use Elastica\Query;
use Elastica\Query\Term;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class CategoryManager extends AbstractManager
{
    /**
     * @var Pagination
    */
    protected $pagination;

    /**
     * @var TransformedFinder $categoryFinder
     */
    protected $categoryFinder;

    /**
     * @var Pagination $pagination
    */
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    public function dummy(Category $category)
    {
        $this->save($category);
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
     * get coupon
     *
     * @param $id
     * @return null | Category
     */
    public function getCategory($id)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Term(['id'=> ['value' => $id]]));
        $result = $this->categoryFinder->find($query);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * Get Categories
     * @param array $params
     * 
     * @return array
     */
    public function getCategories($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['name' => ['order' => 'asc']]);

        $pagination = $this->categoryFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
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
     * @param TransformedFinder $categoryFinder
     * @return CategoryManager
     */
    public function setCategoryFinder($categoryFinder)
    {
        $this->categoryFinder = $categoryFinder;
        return $this;
    }

}