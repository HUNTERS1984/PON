<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SummaryController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Summary:index.html.twig');
    }
}
