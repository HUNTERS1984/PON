<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\News;
use CoreBundle\Paginator\Pagination;

class NewsManager extends AbstractManager
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
     * @param News $news
     *
     * @return News
     */
    public function createNews(News $news)
    {
        $news->setCreatedAt(new \DateTime());
        $this->saveNews($news);
    }

    /**
     * @param News $news
     *
     * @return News
     */
    public function saveNews(News $news)
    {
        $news->setUpdatedAt(new \DateTime());
        return $this->save($news);
    }

    /**
     * @param News $news
     *
     * @return boolean
     */
    public function deleteNews(News $news)
    {
        $news
            ->setDeletedAt(new \DateTime());
        return $this->saveNews($news);
    }

    /**
     * List News
     * @param array $params
     *
     * @return array
     */
    public function listNews($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;

        $conditions = [];

        $conditions['deletedAt'] = [
            'type' => 'is',
            'value' => 'NULL'
        ];

        $orderBy = ['createdAt' => 'DESC'];

        $query = $this->getQuery($conditions, $orderBy, $limit, $offset);

        return $this->pagination->render($query, $limit, $offset);
    }

}