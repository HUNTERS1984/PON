<?php

namespace LandingPageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Apply2Controller extends Controller
{
    public function indexAction()
    {
        return $this->render('LandingPageBundle:Apply2:index.html.twig');
    }
}
