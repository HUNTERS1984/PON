<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\AppUser;
use CoreBundle\Manager\AnalyticsManager;
use CoreBundle\Manager\CouponManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DashboardController extends Controller
{
    public function indexAction(Request $request)
    {
        $params['page_size'] = 4;
        $type = null;
        $dimension = null;
        $report = null;
        if($this->isGranted('ROLE_ADMIN')) {
            $type = $request->query->get('metric');
            $dimension = $request->query->get('dimension');
            $type = !empty($type) ? $type : "sessions";
            $dimension = !empty($dimension) ? $dimension : "date";
            $template = 'AdminBundle:Dashboard:index.html.twig';
            $strDimension = 'ga:'.$dimension;
            $report = $this->getAnalyticsManager()->listReport($strDimension);
            $result = $this->getManager()->listCouponFromAdmin($params);
        } else{
            $template = 'AdminBundle:Dashboard:index-client.html.twig';
            /** @var AppUser $user */
            $user = $this->getUser();
            $result = $this->getManager()->listCouponFromClient($params, $user);
        }

        return $this->render(
            $template,
            [
                'coupons' => $result['data'],
                'report' => $report,
                'type' => $type,
                'dimension' => $dimension
            ]);
    }

    /**
     * @return CouponManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.coupon');
    }

    /**
     * @return AnalyticsManager
    */
    public function getAnalyticsManager()
    {
        return $this->get('pon.manager.analytics');
    }
}
