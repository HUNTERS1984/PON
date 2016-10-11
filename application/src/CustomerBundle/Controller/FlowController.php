<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FlowController extends Controller
{
    public function indexAction()
    {
        return $this->render('CustomerBundle:Flow:index.html.twig');
    }
}
