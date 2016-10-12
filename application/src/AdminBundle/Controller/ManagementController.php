<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ManagementController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Management:index.html.twig');
    }
}
