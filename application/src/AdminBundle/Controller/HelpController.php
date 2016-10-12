<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HelpController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Help:index.html.twig');
    }
}
