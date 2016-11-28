<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Manager\PushSettingManager;
use AdminBundle\Form\Type\PushType;
use CoreBundle\Entity\AppUser;
use CoreBundle\Manager\SegementManager;
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
            $segements = $this->getSegementManager()->getSegementFromAdmin();
            $result = $this->getManager()->getPushSettingManagerFromAdmin($params);
        } else {
            $result = $this->getManager()->getPushSettingManagerFromClient($params, $user);
            $segements = $this->getSegementManager()->getSegementFromClient($user);
        }
        
        $form = $this->createPush($request, $segements);

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

    public function createAction(Request $request)
    {
        return $this->render('AdminBundle:Push:create.html.twig');
    }

    /**
     * @param Request $request
     * @param array $segements
     *
     * @return FormInterface
     */
    public function createPush(Request $request, $segements)
    {

        $form = $this
            ->createForm(PushType::class, null, [
                'segements' => $segements
            ])
            ->handleRequest($request);

        if ($form->isValid()) {

        }

        return $form;
    }

    /**
     * @return SegementManager
     */
    public function getSegementManager()
    {
        return $this->get('pon.manager.segement');
    }
}
