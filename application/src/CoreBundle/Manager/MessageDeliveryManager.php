<?php

namespace CoreBundle\Manager;

use CoreBundle\Entity\Store;
use CoreBundle\Entity\MessageDelivery;
use CoreBundle\Paginator\Pagination;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\TransformedFinder;

class MessageDeliveryManager extends AbstractManager
{
    /**
     * @var Pagination
     */
    protected $pagination;

    /**
     * @var TransformedFinder $messageDeliveryFinder
     */
    protected $messageDeliveryFinder;

    /**
     * @var Pagination $pagination
     */
    public function setPagination(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    public function dummy(MessageDelivery $messageDelivery)
    {
        $this->save($messageDelivery);
    }

    /**
     * @param MessageDelivery $messageDelivery
     *
     * @return MessageDelivery
     */
    public function createMessageDelivery(MessageDelivery $messageDelivery)
    {
        return $this->save($messageDelivery);
    }

    /**
     * @param mixed $messageDeliveryFinder
     * @return MessageDeliveryManager
     */
    public function setMessageDeliveryFinder($messageDeliveryFinder)
    {
        $this->messageDeliveryFinder = $messageDeliveryFinder;
        return $this;
    }

}