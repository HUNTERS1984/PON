<?php

namespace AdminBundle\Controller;

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
     */
    public function indexAction(Request $request)
    {
        $params = $request->query->all();
        $params['query'] = isset($params['query']) ? $params['query'] : '';
        $user = $this->getUser();
        if ($this->isGranted('ROLE_ADMIN')) {
            $result = $this->getManager()->getUseListManagerFromAdmin($params);
        }
        elseif ($this->isGranted('ROLE_CLIENT')) {
            $result = $this->getManager()->getUseListManagerFromClient($params, $user);
        }

        return $this->render(
            'AdminBundle:Management:index.html.twig',
            [
                'lists' => $result['data'],
                'pagination' => $result['pagination'],
                'params' => $params
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
