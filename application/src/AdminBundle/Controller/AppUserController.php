<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\AppUserType;
use CoreBundle\Entity\AppUser;
use CoreBundle\Manager\SocialProfileManager;
use CoreBundle\Manager\StoreManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Manager\AppUserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
        if ($this->isGranted('ROLE_ADMIN')) {
            $result = $this->getManager()->getAppUserManagerFromAdmin($params);
        } else {
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
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(
            AppUserType::class,
            null,
            [
                'roles' => [
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_CLIENT' => 'ROLE_CLIENT'
                ]
            ]
        )->add('plainPassword', PasswordType::class, [
            'label' => 'パスワード',
            'required' => true,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'パスワード',
                'autocomplete' => 'off',
            ]
        ]);

        $form = $form->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var AppUser $appUser */
            $appUser = $form->getData();

            if($appUser->getStore()) {
                $storeId = $appUser->getStore()->getId();
                $store = $this->getStoreManager()->getStore($storeId);
                if (!$store) {
                    return $this->getFailureMessage('店を見つけることができませんでした！');
                }
                $appUser->setStore($store);
            }

            $appUser->setAppUserId($this->getManager()->createID('US'));
            if ($fileUpload = $appUser->getImageFile()) {
                $fileUrl = $this->getManager()->uploadAvatar($fileUpload, $appUser->getAppUserId());
                $appUser->setAvatarUrl($fileUrl);
            }
            $appUser->setRoles([$appUser->getRole()]);
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

    public function testAction()
    {
        /** @var SocialProfileManager $socialProfileManager */
        $socialProfileManager = $this->get('pon.manager.social_profile');
        $socialProfile = $socialProfileManager->findOneById(4);
        $date = clone $socialProfile->getRequestedAt();
        $date->modify('-5 days');
        $socialProfile->setRequestedAt($date);
       $socialProfileManager->saveSocialProfile($socialProfile);
    }

    /**
     * Edit AppUser Action
     *
     * @return Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function editAction(Request $request, $id)
    {
        $appUser = $this->getManager()->getAppUser($id);
        if (!$appUser) {
            throw $this->createNotFoundException('ユーザーは見つかりませんでした。');
        }
        $form = $this->createForm(
            AppUserType::class,
            $appUser,
            [
                'roles' => [
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    'ROLE_CLIENT' => 'ROLE_CLIENT'
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
            'label' => 'パスワード',
            'required' => false,
            'attr' => [
                'class' => 'form-control',
                'placeholder' => 'パスワード',
                'autocomplete' => 'off',
            ]
        ])->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var AppUser $appUser */
            $appUser = $form->getData();

            if($appUser->getStore()) {
                $storeId = $appUser->getStore()->getId();
                $store = $this->getStoreManager()->getStore($storeId);
                if (!$store) {
                    return $this->getFailureMessage('店を見つけることができませんでした！');
                }
                $appUser->setStore($store);
            }

            if ($fileUpload = $appUser->getImageFile()) {
                $fileUrl = $this->getManager()->uploadAvatar($fileUpload, $appUser->getAppUserId());
                $appUser->setAvatarUrl($fileUrl);
            }

            $appUser->setRoles([$appUser->getRole()]);
            $appUser = $this->getManager()->saveAppUser($appUser);

            if (!$appUser) {
                return $this->getFailureMessage('ユーザーの作成に失敗しました');
            }
            return $this->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        return $this->render(
            'AdminBundle:AppUser:edit.html.twig',
            [
                'form' => $form->createView(),
                'appUser' => $appUser
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
        return $this->redirectToRoute('admin_app_user');

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

    /**
     * @return StoreManager
     */
    public function getStoreManager()
    {
        return $this->get('pon.manager.store');
    }
}
