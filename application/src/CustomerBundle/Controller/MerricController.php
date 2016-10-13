<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MerricController extends Controller
{
    public function indexAction()
    {
        return $this->render('CustomerBundle:Merric:index.html.twig');
    }
}
