<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\AppUser;
use CoreBundle\Manager\AppUserManager;
use CoreBundle\Utils\StringGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        /** @var AppUser $user */
        $user = $this->getUser();
//       var_dump($user);die();
        if(!is_null($user) && $this->isGranted('ROLE_AGENT')){
            return $this->redirectToRoute('admin_homepage');
        }

        return $this->render('AdminBundle:Security:login.html.twig',
            [
//                'csrf_token' => 'xx',
                'error' => $error,
                'lastUsername' => $lastUsername,
            ]
        );
    }

    public function forgotAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'label' => 'Eメールアドレス',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Eメールアドレス',
                    'autocomplete' => 'off',
                ]
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $email = $data['email'];
            /**@var AppUser $appUser */
            $appUser = $this->getManager()->getAppUserByEmail($email);
            if(!$appUser) {
                $this->addFlash('forgot_password_error', "電子メールが見つかりませんでした");
            }else{
                $this->resetPassword($appUser);
                return $this->render('AdminBundle:Security:forgot_success.html.twig');
            }

        }
        return $this->render('AdminBundle:Security:forgot.html.twig',[
            'form' => $form->createView(),
        ]);
    }

    public function resetAction($id, Request $request)
    {
        $token = $request->get('token');
        $appUser = $this->getManager()->getAppUserById($id);
        if(!$appUser) {
            throw $this->createNotFoundException('ユーザーは見つかりませんでした。');
        }

        if($appUser->getResetToken() != $token || $appUser->getTokenExpired() < new \DateTime()) {
            throw $this->createNotFoundException('トークンが失効しました');
        }

        $form = $this->createFormBuilder()
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'パスワードフィールドは一致する必要があります。',
                'options' => array('attr' => array('class' => 'form-control')),
                'required' => true,
                'first_options'  => [
                    'label' => 'パスワード',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'パスワード'
                    ]
                ],
                'second_options' => [
                    'label' => 'パスワードを認証する',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'パスワードを認証する'
                    ]
                ],
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // perform some action...
            $data = $form->getData();
            $appUser
                ->setResetToken(null)
                ->setTokenExpired(null)
                ->setPlainPassword($data['password']);

            $appUser = $this->getManager()->saveAppUser($appUser);
            if($appUser) {
                return $this->render('AdminBundle:Security:success.html.twig',[
                ]);
            }else{
                return $this->render('AdminBundle:Security:false.html.twig',[
                ]);
            }

        }


        return $this->render('AdminBundle:Security:reset.html.twig',[
            'token' => $token,
            'id' => $id,
            'form' => $form->createView(),
        ]);
    }

    public function resetPassword(AppUser $appUser)
    {
        $expiredTime = new \DateTime();
        $expiredTime->modify("+24 hours");
        $appUser
            ->setTokenExpired($expiredTime)
            ->setResetToken(StringGenerator::secureGenerate());
        $subject = "[PON]パスワードリセット通知";
        $body = $this->get('twig')->render(
            'AdminBundle:Security:forgot_email.html.twig',
            [
                'appUser' => $appUser
            ]
        );
        $sender = $this->getParameter('mailer_sender');
        $senderName = $this->getParameter('mailer_sender_name');
        $this->getManager()->saveAppUser($appUser);
        $this->getManager()->sendForGotPasswordEmail($appUser, $subject, $body, $sender, $senderName);

    }

    public function logoutAction()
    {
        return $this->redirectToRoute('admin_login');
    }

    /**
     * @return AppUserManager
    */
    public function getManager()
    {
        return $this->get('pon.manager.app_user');
    }
}
