<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CustomerController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Customer:index.html.twig');
    }
}
