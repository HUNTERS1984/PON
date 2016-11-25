<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CoreBundle\Manager\UseListManager;

class ManagementController extends Controller
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
        $params['query'] = isset($params['query']) ? urlencode($params['query']) : '';
        $user = $this->getUser();
        if ($this->isGranted('ROLE_ADMIN')) {
            $result = $this->getManager()->getUseListManagerFromAdmin($params);
        } else {
            $result = $this->getManager()->getUseListManagerFromClient($params, $user);
        }

        return $this->render(
            'AdminBundle:Management:index.html.twig',
            [
                'lists' => $result['data'],
                'pagination' => $result['pagination'],
                'query' => urldecode($params['query'])
            ]
        );

    }

    /**
     * @return UseListManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.use_list');
    }
}
