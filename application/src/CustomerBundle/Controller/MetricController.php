<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MetricController extends Controller
{
    public function indexAction()
    {
        return $this->render('CustomerBundle:Metric:index.html.twig');
    }
}
