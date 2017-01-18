<?php

namespace CoreBundle\Event;

use CoreBundle\Entity\Store;
use Symfony\Component\EventDispatcher\Event;

class StoreEvents extends Event
{

    const PRE_CREATE = 'pon.event.store.pre_create';

    const POST_CREATE = 'pon.event.store.post_create';

    /**
     * @var Store
     */
    protected $store;

    /**
     * @param Store $store
     * @return StoreEvents
     */
    public function setStore(Store $store): StoreEvents
    {
        $this->store = $store;

        return $this;
    }

    /**
     * @return Store
     */
    public function getStore(): Store
    {
        return $this->store;
    }
}
