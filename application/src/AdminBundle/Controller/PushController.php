<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PushController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Push:index.html.twig');
    }

    public function createAction(Request $request)
    {
        return $this->render('AdminBundle:Push:create.html.twig');
    }
}
