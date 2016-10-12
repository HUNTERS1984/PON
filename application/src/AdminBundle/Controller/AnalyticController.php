<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AnalyticController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Analytic:index.html.twig');
    }
}
