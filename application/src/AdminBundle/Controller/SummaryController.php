<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\CouponType;
use CoreBundle\Manager\CouponManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SummaryController extends Controller
{
    public function indexAction(Request $request)
    {
        $params = $request->query->all();
        $params['query'] = isset($params['query']) ? $params['query'] : '';
        $result = $this->getManager()->listCoupon($params);

        return $this->render(
            'AdminBundle:Summary:index.html.twig',
            [
                'coupons' => $result['data'],
                'pagination' =>  $result['pagination'],
                'params' => $params
            ]);
    }

    public function editAction(Request $request, $id)
    {
        $coupon = $this->getManager()->getCoupon($id);

        if (!$coupon) {
            throw $this->createNotFoundException('Unable to find Coupon.');
        }

        $form = $this->createForm(CouponType::class, $coupon)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return new Response(json_encode(array('status'=>'success')));
        }

        return $this->render(
            'AdminBundle:Summary:edit.html.twig',
            [
                'form' => $form->createView(),
                'coupon' => $coupon
            ]
        );

    }

    /**
     * @return CouponManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.coupon');
    }

}
