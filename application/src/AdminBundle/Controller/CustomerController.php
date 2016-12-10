<?php

namespace AdminBundle\Controller;

use CoreBundle\Manager\AppUserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
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
        if ($this->isGranted('ROLE_ADMIN')) {
            $result = $this->getManager()->getCustomerFromAdmin($params);
        } else {
            $result = $this->getManager()->getCustomerFromClient($params, $this->getUser());
        }

        return $this->render(
            'AdminBundle:Customer:index.html.twig',
            [
                'customers' => $result['data'],
                'pagination' => $result['pagination'],
                'params' => $params
            ]
        );
    }

    /**
     * Active AppUser Action
     *
     * @return Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function activeAction($id)
    {
        $appUser = $this->getManager()->getAppUser($id);
        if (!$appUser) {
            throw $this->createNotFoundException('ユーザーは見つかりませんでした。');
        }

        $enabled = $appUser->isEnabled()? false: true;
        $appUser->setEnabled($enabled);

        $this->getManager()->saveAppUser($appUser);
        return $this->redirectToRoute('admin_customer');

    }

    /**
     * View Customer Action
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function viewAction(Request $request, $id)
    {
        $appUser = $this->getManager()->getAppUser($id);
        if (!$appUser) {
            throw $this->createNotFoundException('ユーザーは見つかりませんでした。');
        }

        return $this->render(
            'AdminBundle:Customer:view.html.twig',
            [
                'customer' => $appUser
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
}
