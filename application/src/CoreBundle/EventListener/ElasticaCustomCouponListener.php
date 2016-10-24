<?php

namespace CoreBundle\EventListener;

use CoreBundle\Entity\AppUser;
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

    /**
     * @var $securityContext
     */
    private $securityContext;

    /**
     * getUser
     *
     * @return null|AppUser
     */
    public function getUser()
    {
        if (null === $token = $this->securityContext->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }

    public function addCustomProperty(TransformEvent $event)
    {
        $object = $event->getObject();
    }

    public static function getSubscribedEvents()
    {
        return array(
            //TransformEvent::PRE_TRANSFORM => 'addCustomProperty',
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

    /**
     * @param mixed $securityContext
     * @return SerializeListener
     */
    public function setSecurityContext($securityContext)
    {
        $this->securityContext = $securityContext;
        return $this;
    }

}
