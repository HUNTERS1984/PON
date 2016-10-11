<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Contact3Controller extends Controller
{
    public function indexAction()
    {
        return $this->render('CustomerBundle:Contact3:index.html.twig');
    }
}
