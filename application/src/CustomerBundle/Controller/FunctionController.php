<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FunctionController extends Controller
{
    public function indexAction()
    {
        return $this->render('CustomerBundle:Function:index.html.twig');
    }
}
