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
        if(!is_null($user) && $this->isGranted('ROLE_AGENT')){
            return $this->redirectToRoute('admin_homepage');
        }

        return $this->render('AdminBundle:Security:login.html.twig',
            [
                'error' => $error,
                'lastUsername' => $lastUsername,
            ]
        );
    }

    public function forgotAction(Request $request)
    {
        $form = $this->createFormBuilder()
            ->add('email', EmailType::class, [
                'label' => 'form.app_user.email',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'form.app_user.email',
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
                $this->addFlash('forgot_password_error', $this->get('translator')->trans("security.reset.email_not_found"));
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
            throw $this->createNotFoundException($this->get('translator')->trans('security.reset.user_not_found'));
        }

        if($appUser->getResetToken() != $token || $appUser->getTokenExpired() < new \DateTime()) {
            throw $this->createNotFoundException($this->get('translator')->trans('security.reset.token_expired'));
        }

        $form = $this->createFormBuilder()
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => $this->get('translator')->trans('security.reset.invalid_message'),
                'options' => array('attr' => array('class' => 'form-control')),
                'required' => true,
                'first_options'  => [
                    'label' => 'security.reset.first_password',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'security.reset.first_password'
                    ]
                ],
                'second_options' => [
                    'label' => 'security.reset.second_password',
                    'attr' => [
                        'class' => 'form-control',
                        'placeholder' => 'security.reset.second_password'
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
        $subject = $this->get('translator')->trans("security.reset.subject");
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
