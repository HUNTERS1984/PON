<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\PushSetting;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Manager\PushSettingManager;
use AdminBundle\Form\Type\PushType;
use CoreBundle\Entity\AppUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PushController extends Controller
{
    /**
     * List all Use_list
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function indexAction(Request $request)
    {
        $params = $request->query->all();
        $params['query'] = isset($params['query']) ? $params['query'] : '';
        $user = $this->getUser();

        if ($this->isGranted('ROLE_ADMIN')) {
            $result = $this->getManager()->getPushSettingManagerFromAdmin($params);
        } else {
            $result = $this->getManager()->getPushSettingManagerFromClient($params, $user);
        }
        $form = $this->createPush($request, $this->getParameter('segments'));

        return $this->render(
            'AdminBundle:Push:index.html.twig',
            [
                'pushs' => $result['data'],
                'pagination' => $result['pagination'],
                'query' => ($params['query']),
                'params' => $params,
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @return PushSettingManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.push_setting');
    }

    public function updateAction(Request $request)
    {
        if(!$request->isXmlHttpRequest() ) {
            return $this->get('pon.utils.response')->getFailureMessage('common.status_false.create');
        }

        $pushSetting = new PushSetting();
        $pushSetting->setDeliveryTime(new \DateTime());
        $form = $this->createPush($request, $this->getParameter('segments'), $pushSetting);

        if (count($errors = $form->getErrors(true)) > 0) {
            return $this->get('pon.utils.response')->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        /** @var PushSetting $pushSetting */
        $pushSetting = $form->getData();
        $pushSetting->setStatus(1);
        if(!$this->isGranted("ROLE_ADMIN")) {
            $pushSetting->setStore($this->getUser()->getStore());
        }

        try{
            $pushSetting = $this->getManager()->createPushSetting($pushSetting);
        }catch (\Exception $ex) {
            return $this->get('pon.utils.response')->getFailureMessage($ex->getMessage());
        }

        if (!$pushSetting) {
            return $this->get('pon.utils.response')->getFailureMessage('common.status_false.create');
        }

        return $this->get('pon.utils.response')->getSuccessMessage();
    }

    public function editAction(Request $request, $id)
    {
        $pushSetting = $this->getManager()->getPushSetting($id);
        if (!$pushSetting) {
            throw $this->createNotFoundException($this->get('translator')->trans('push.edit.push_not_found'));
        }

        $form = $this->createPush($request, $this->getParameter('segments'), $pushSetting);

        return $this->render(
            'AdminBundle:Push:edit.html.twig',
            [
                'form' => $form->createView(),
                'pushSetting' => $pushSetting
            ]
        );

    }

    /**
     * @param Request $request
     * @param array $segments
     * @param mixed $data
     *
     * @return FormInterface
     */
    public function createPush(Request $request, $segments, $data = null)
    {
        $form = $this
            ->createForm(PushType::class, $data, [
                'segments' => $segments
            ])
            ->handleRequest($request);

        return $form;
    }
}
