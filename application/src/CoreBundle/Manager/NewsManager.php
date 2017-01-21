<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\News;
use CoreBundle\Event\NewsEvents;
use CoreBundle\Paginator\Pagination;
use Elastica\Filter\Missing;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class NewsManager extends AbstractManager
{
    /**
     * @var TransformedFinder $storeFinder
     */
    protected $newsFinder;
    /**
     * @var Pagination
     */
    protected $pagination;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

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
        if(!$news->getNewsId()) {
            $news->setNewsId($this->createID('NE'));
        }
        $news->setCreatedAt(new \DateTime());
        return $this->saveNews($news);
    }

    /**
     * @param News $news
     *
     * @return News
     */
    public function saveNews(News $news)
    {
        $news->setUpdatedAt(new \DateTime());
        $newsEvents = new NewsEvents();
        $newsEvents->setNews($news);
        $this->dispatcher->dispatch(NewsEvents::PRE_CREATE, $newsEvents);
        $news = $this->save($news);
        $this->dispatcher->dispatch(NewsEvents::POST_CREATE, $newsEvents);

        return $news;
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

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['updatedAt' => ['order' => 'desc']]);
        $query->setQuery(new Query\MatchAll());

        $pagination = $this->newsFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * @param mixed $newsFinder
     * @return NewsManager
     */
    public function setNewsFinder($newsFinder)
    {
        $this->newsFinder = $newsFinder;
        return $this;
    }

    public function getNewsManagerFromAdmin($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['createdAt' => ['order' => 'desc']]);

        $boolQuery = new Query\BoolQuery();
        if (!empty($queryString)) {
            $multiMatchQuery = new Query\MultiMatch();
            $multiMatchQuery->setFields(['title','description','introduction','newsCategory.name']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery
                ->addMust($multiMatchQuery);
        } else {
            $boolQuery->addMust(new Query\MatchAll());
        }

        $query->setQuery($boolQuery);

        $pagination = $this->newsFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    public function getNewsManagerFromClient($params, AppUser $user)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'desc';
        $sortBy = 'updatedAt';
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort([$sortBy => ['order' => $orderBy]]);

        $boolQuery = new Query\BoolQuery();
        if (!empty($queryString)) {
            $multiMatchQuery = new Query\MultiMatch();
            $multiMatchQuery->setFields(['title','description','introduction','newsCategory.name']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery
                ->addMust($multiMatchQuery);
        } else {
            $boolQuery
                ->addMust(new Query\MatchAll());
        }

        $boolQuery->addMust(new Query\Term(['store.id'=> $user->getStore()->getId()]));
        $query->setQuery($boolQuery);

        $pagination = $this->newsFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * get News
     *
     * @param $id
     * @return null | News
     */
    public function getNews($id)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Query\Term(['id' => ['value' => $id]]));
        $result = $this->newsFinder->find($query);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * get News number
     *
     * @return integer
     */
    public function getNewsNumber()
    {
        $query = new Query();
        $query->setQuery(new Query\MatchAll());
        $pagination = $this->newsFinder->createPaginatorAdapter($query);
        return $pagination->getTotalHits();
    }

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }


}