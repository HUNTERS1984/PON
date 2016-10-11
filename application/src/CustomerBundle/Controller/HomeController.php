<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('CustomerBundle:Home:index.html.twig');
    }
}
