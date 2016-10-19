<?php

namespace LandingPageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('LandingPageBundle:Home:index.html.twig');
    }
}
