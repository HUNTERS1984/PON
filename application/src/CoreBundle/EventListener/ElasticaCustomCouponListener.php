<?php

namespace CoreBundle\EventListener;

use CoreBundle\Entity\Category;
use CoreBundle\Entity\Coupon;
use CoreBundle\Manager\CouponManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use FOS\ElasticaBundle\Event\TransformEvent;

class ElasticaCustomCouponListener implements EventSubscriberInterface
{
    /**
     * @var CouponManager $couponManager
    */
    private $couponManager;

    public function addCustomProperty(TransformEvent $event)
    {
        $object = $event->getObject();

        if($object instanceof Category) {
            $coupons = $this->getCouponManager()->getCouponsByCategory($object);
            $object->setCoupons($coupons);
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            TransformEvent::PRE_TRANSFORM => 'addCustomProperty',
        );
    }

    /**
     * @param CouponManager $couponManager
     * @return ElasticaCustomCouponListener
     */
    public function setCouponManager(CouponManager $couponManager)
    {
        $this->couponManager = $couponManager;
        return $this;
    }

    /**
     * @return CouponManager
     */
    public function getCouponManager()
    {
        return $this->couponManager;
    }

}
