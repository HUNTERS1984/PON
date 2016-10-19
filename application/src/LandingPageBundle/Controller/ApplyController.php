<?php

namespace LandingPageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApplyController extends Controller
{
    public function indexAction()
    {
        return $this->render('LandingPageBundle:Apply:index.html.twig');
    }
}
