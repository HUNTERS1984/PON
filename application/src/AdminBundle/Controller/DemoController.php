<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DemoController extends Controller
{
    public function connectAction()
    {
        return $this->render('AdminBundle:Demo:index.html.twig');
    }

    public function tokenAction()
    {
        var_dump($this->container->get('security.token_storage')->getToken());die();
        var_dump($this->getUser());die();
        echo 'abc';die();
        var_dump($this->getUser());die();
    }
}
