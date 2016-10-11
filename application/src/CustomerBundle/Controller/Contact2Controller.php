<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Contact2Controller extends Controller
{
    public function indexAction()
    {
        return $this->render('CustomerBundle:Contact2:index.html.twig');
    }
}
