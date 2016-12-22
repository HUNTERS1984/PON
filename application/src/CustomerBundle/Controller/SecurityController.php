<?php

namespace CustomerBundle\Controller;

use CoreBundle\Manager\AppUserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function forgotAction($id, Request $request)
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
                return $this->render('CustomerBundle:Security:success.html.twig',[
                ]);
            }else{
                return $this->render('CustomerBundle:Security:false.html.twig',[
                ]);
            }

        }


        return $this->render('CustomerBundle:Security:forgot.html.twig',[
            'token' => $token,
            'id' => $id,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @return AppUserManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.app_user');
    }
}
