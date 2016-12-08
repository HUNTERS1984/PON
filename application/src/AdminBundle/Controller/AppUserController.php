<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\AppUserType;
use CoreBundle\Entity\AppUser;
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
     * Create User Action
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(AppUserType::class);

        $form = $form->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var AppUser $appUser */
            $appUser = $form->getData();
            $appUser->setAppUserId($this->getManager()->createID('US'));
            if ($fileUpload = $appUser->getImageFile()) {
                $fileUrl = $this->getManager()->uploadAvatar($fileUpload, $appUser->getAppUserId());
                $appUser->setAvatarUrl($fileUrl);
            }

            $appUser = $this->getManager()->createAppUser($appUser);

            if (!$appUser) {
                return $this->getFailureMessage('ユーザーの作成に失敗しました');
            }
            return $this->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        return $this->render(
            'AdminBundle:AppUser:create.html.twig',
            [
                'form' => $form->createView()
            ]
        );

    }

    /**
     * @param string $message
     * @return Response
     */
    public function getSuccessMessage($message = '')
    {
        return new Response(json_encode(['status' => true, 'message' => $message]));
    }

    /**
     * @param string $message
     * @return Response
     */
    public function getFailureMessage($message = '')
    {
        return new Response(json_encode(['status' => false, 'message' => $message]));
    }

    /**
     * @return AppUserManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.app_user');
    }
}
