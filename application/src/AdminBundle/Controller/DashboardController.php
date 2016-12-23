<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\AppUser;
use CoreBundle\Manager\CouponManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
        $params['page_size'] = 4;

        if($this->isGranted('ROLE_ADMIN')) {
            $result = $this->getManager()->listCouponFromAdmin($params);
        } else{
            /** @var AppUser $user */
            $user = $this->getUser();
            $result = $this->getManager()->listCouponFromClient($params, $user);
        }

        return $this->render(
            'AdminBundle:Dashboard:index.html.twig',
            [
                'coupons' => $result['data'],
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
