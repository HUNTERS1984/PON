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
            throw $this->createNotFoundException('クーポンが見つかりません。');
        }

        $form = $this->createForm(CouponType::class, $coupon)->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {
            $coupon = $form->getData();
            $coupon = $this->getManager()->saveCoupon($coupon);
            if(!$coupon) {
                return $this->getFailureMessage('クーポンの作成に失敗しました');
            }
            return $this->getSuccessMessage();
        }

        if(count($errors = $form->getErrors(true)) > 0) {
            return $this->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
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
     * @param string $message
     * @return Response
     */
    public function getSuccessMessage($message = '')
    {
        return new Response(json_encode(['status'=> true, 'message' => $message]));
    }

    /**
     * @param string $message
     * @return Response
     */
    public function getFailureMessage($message = '')
    {
        return new Response(json_encode(['status'=> false, 'message' => $message]));
    }

    /**
     * @return CouponManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.coupon');
    }

}
