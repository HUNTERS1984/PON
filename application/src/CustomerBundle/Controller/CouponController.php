<?php

namespace CustomerBundle\Controller;

use CoreBundle\Manager\AppUserManager;
use CoreBundle\Manager\CouponManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;

class CouponController extends Controller
{
    public function linkAction($id)
    {
        $coupon = $this->getManager()->getCoupon($id);
        if(!$coupon) {
            throw $this->createNotFoundException("Coupon not found!");
        }

        return $this->render('CustomerBundle:Coupon:link.html.twig',[
            'coupon' => $coupon
        ]);
    }

    /**
     * @return CouponManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.coupon');
    }
}
