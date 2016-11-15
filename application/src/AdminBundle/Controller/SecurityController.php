<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\AppUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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

    public function forgotAction()
    {
        return $this->render('AdminBundle:Security:forgot.html.twig');
    }

    public function logoutAction()
    {
        return $this->redirectToRoute('admin_login');
    }
}
