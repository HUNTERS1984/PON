<?php

namespace AdminBundle\Controller;

use CoreBundle\Manager\CouponManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SummaryController extends Controller
{
    public function indexAction(Request $request)
    {
        $params = $request->request->all();

        $result = $this->getManager()->listCoupon($params);

        return $this->render(
            'AdminBundle:Summary:index.html.twig',
            [
                'coupons' => $result['data'],
                'pagination' =>  $result['pagination'],
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
