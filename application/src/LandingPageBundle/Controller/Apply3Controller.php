<?php

namespace LandingPageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Apply3Controller extends Controller
{
    public function indexAction()
    {
        return $this->render('LandingPageBundle:Apply3:index.html.twig');
    }
}
