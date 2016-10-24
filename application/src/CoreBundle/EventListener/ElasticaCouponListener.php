<?php

namespace CoreBundle\EventListener;

use CoreBundle\Entity\Category;
use CoreBundle\Entity\Coupon;
use CoreBundle\Manager\CouponManager;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use FOS\ElasticaBundle\Persister\ObjectPersister;

class ElasticaCouponListener implements EventSubscriber
{
    /**
     * @var ObjectPersister $objectPersisterCoupon
    */
    private $objectPersisterCoupon;

    /**
     * @var ObjectPersister $objectPersisterCategory
    */
    private $objectPersisterCategory;

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        $this->setCoupon($entity);
    }

    public function setCategory(Coupon $coupon)
    {
        $category = $coupon->getStore()->getCategory();
        $this->objectPersisterCategory->replaceOne($category);
    }

    public function setCoupon($entity)
    {
        if($entity instanceof Coupon) {
            $this->setCategory($entity);
        }
    }

    /**
     * @param \Doctrine\ORM\Event\LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $entity = $eventArgs->getEntity();
        $this->setCoupon($entity);
    }

    /**
     * @param mixed $objectPersisterCoupon
     * @return ElasticaCouponListener
     */
    public function setObjectPersisterCoupon($objectPersisterCoupon)
    {
        $this->objectPersisterCoupon = $objectPersisterCoupon;
        return $this;
    }

    /**
     * @param mixed $objectPersisterCategory
     * @return ElasticaCouponListener
     */
    public function setObjectPersisterCategory($objectPersisterCategory)
    {
        $this->objectPersisterCategory = $objectPersisterCategory;
        return $this;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
        );
    }

}
