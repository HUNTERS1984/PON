<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AboutUSController extends Controller
{
    public function indexAction()
    {
        return $this->render('CustomerBundle:AboutUS:index.html.twig');
    }
}
