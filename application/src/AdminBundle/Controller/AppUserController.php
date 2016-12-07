<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Manager\AppUserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AppUserController extends Controller
{
    /**
     * List all User
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function indexAction(Request $request)
    {
        $params = $request->query->all();
        $params['query'] = isset($params['query']) ? $params['query'] : '';
        if($this->isGranted('ROLE_ADMIN')) {
            $result = $this->getManager()->getAppUserManagerFromAdmin($params);
        }else{
            $result = $this->getManager()->getAppUserManagerFromClient($params, $this->getUser());
        }

        return $this->render(
            'AdminBundle:AppUser:index.html.twig',
            [
                'users' => $result['data'],
                'pagination' => $result['pagination'],
                'params' => $params
            ]
        );
    }

    /**
     * @return AppUserManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.app_user');
    }

    public function createAction(Request $request)
    {
        return $this->render('AdminBundle:Push:create.html.twig');
    }
}
