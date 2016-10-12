<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PushController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Push:index.html.twig');
    }
}
