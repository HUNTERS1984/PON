<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MerritController extends Controller
{
    public function indexAction()
    {
        return $this->render('CustomerBundle:Merrit:index.html.twig');
    }
}
