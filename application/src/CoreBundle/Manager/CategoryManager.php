<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
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
    public function createCategory(Category $category)
    {
        if(!$category->getCategoryId()) {
            $category->setCategoryId($this->createID('CA'));
        }
        $category->setCreatedAt(new \DateTime());
        return $this->saveCategory($category);
    }

    /**
     * @param Category $category
     *
     * @return boolean
     */
    public function deleteCategory(Category $category)
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
        $query->setQuery(new Term(['id' => ['value' => $id]]));
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
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['createdAt' => ['order' => 'desc']]);

        $boolQuery = new Query\BoolQuery();

        if (!empty($queryString)) {
            $keywordQuery = new Query\QueryString($queryString);
            $keywordQuery->setQuery($queryString);
            $keywordQuery->setAnalyzer('keyword_analyzer');
            $query->setFields(['name']);
            $boolQuery->addMust($keywordQuery);
        } else {
            $boolQuery
                ->addMust(new Query\MatchAll());
        }

        $query->setQuery($boolQuery);

        $pagination = $this->categoryFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
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