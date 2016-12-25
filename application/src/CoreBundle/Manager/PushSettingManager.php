<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Store;
use CoreBundle\Entity\PushSetting;
use CoreBundle\Event\NotificationEvents;
use CoreBundle\Paginator\Pagination;
use Elastica\Filter\Missing;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PushSettingManager extends AbstractManager
{
    /**
     * @var Pagination
     */
    protected $pagination;

    /**
     * @var TransformedFinder $pushSettingFinder
     */
    protected $pushSettingFinder;

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
     * @param PushSetting $pushSetting
     *
     * @return PushSetting
     */
    public function createPushSetting(PushSetting $pushSetting)
    {
        $pushSetting
            ->setCreatedAt(new \DateTime());
        return $this->savePushSetting($pushSetting);
    }

    /**
     * @param PushSetting $pushSetting
     *
     * @return PushSetting
     */
    public function savePushSetting(PushSetting $pushSetting)
    {
        $notificationEvent = new NotificationEvents();
        $pushSetting->setUpdatedAt(new \DateTime());
        $notificationEvent->setPushSetting($pushSetting);
        $this->dispatcher->dispatch(NotificationEvents::PRE_CREATE, $notificationEvent);
        $result = $this->save($pushSetting);
        $this->dispatcher->dispatch(NotificationEvents::POST_CREATE, $notificationEvent);
        return $result;
    }

    /**
     * @param PushSetting $pushSetting
     *
     * @return boolean
     */
    public function deleteSegment(PushSetting $pushSetting)
    {
        $pushSetting
            ->setDeletedAt(new \DateTime());
        return $this->savePushSetting($pushSetting);
    }

    /**
     * @param mixed $pushSettingFinder
     * @return PushSettingManager
     */
    public function setPushSettingFinder($pushSettingFinder)
    {
        $this->pushSettingFinder = $pushSettingFinder;
        return $this;
    }

    public function getPushSettingManagerFromAdmin($params)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort(['createdAt' => ['order' => 'desc']]);

        $statusQuery = new Query\BoolQuery();

        if (isset($params['status']) && in_array($params['status'], ["1", "0"])) {
            $status = (int)$params["status"];
            $statusQuery->addShould(new Query\Term(['status' => $status]));
        }

        $boolQuery = new Query\BoolQuery();

        if (!empty($queryString)) {
            $multiMatchQuery = new Query\MultiMatch();
            $multiMatchQuery->setFields(['title', 'message']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery
                ->addMust($multiMatchQuery)
                ->addMust($statusQuery);
        } else {
            $boolQuery
                ->addMust(new Query\MatchAll())
                ->addMust($statusQuery);;
        }

        $query->setQuery($boolQuery);

        $pagination = $this->pushSettingFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    public function getPushSettingManagerFromClient($params, AppUser $user)
    {
        $limit = isset($params['page_size']) ? $params['page_size'] : 10;
        $offset = isset($params['page_index']) ? $this->pagination->getOffsetNumber($params['page_index'], $limit) : 0;
        $orderBy = isset($params['order_by']) ? $params['order_by'] : 'desc';
        $sortBy = 'updatedAt';
        $queryString = isset($params['query']) ? $params['query'] : '';

        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->addSort([$sortBy => ['order' => $orderBy]]);

        $statusQuery = new Query\BoolQuery();
        if (isset($params['status']) && in_array($params['status'], ["1", "0"])) {
            $status = (int)$params["status"];
            $statusQuery->addShould(new Query\Term(['status' => $status]));
        }

        $boolQuery = new Query\BoolQuery();
        if (!empty($queryString)) {
            $multiMatchQuery = new Query\MultiMatch();
            $multiMatchQuery->setFields(['title', 'message']);
            $multiMatchQuery->setType('cross_fields');
            $multiMatchQuery->setAnalyzer('standard');
            $multiMatchQuery->setQuery($queryString);
            $boolQuery
                ->addMust($multiMatchQuery)
                ->addMust($statusQuery);
        } else {
            $boolQuery
                ->addMust(new Query\MatchAll())
                ->addMust($statusQuery);
        }
        $boolQuery->addMust(new Query\Term(['store.id' => $user->getStore()->getId()]));
        $query->setQuery($boolQuery);

        $pagination = $this->pushSettingFinder->createPaginatorAdapter($query);
        $transformedPartialResults = $pagination->getResults($offset, $limit);
        $results = $transformedPartialResults->toArray();
        $total = $transformedPartialResults->getTotalHits();
        return $this->pagination->response($results, $total, $limit, $offset);
    }

    /**
     * get PushSetting
     *
     * @param $id
     * @return null | PushSetting
     */
    public function getPushSetting($id)
    {
        $query = new Query();
        $query->setPostFilter(new Missing('deletedAt'));
        $query->setQuery(new Query\Term(['id' => ['value' => $id]]));
        $result = $this->pushSettingFinder->find($query);
        return !empty($result) ? $result[0] : null;
    }

    /**
     * @param EventDispatcherInterface $dispatcher
     * @return PushSettingManager
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;

        return $this;
    }

}