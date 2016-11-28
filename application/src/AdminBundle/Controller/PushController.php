<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Manager\PushSettingManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

        return $this->render(
            'AdminBundle:Push:index.html.twig',
            [
                'pushs' => $result['data'],
                'pagination' => $result['pagination'],
                'query' => ($params['query']),
                'params' => $params
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

    public function createAction(Request $request)
    {
        return $this->render('AdminBundle:Push:create.html.twig');
    }
}
