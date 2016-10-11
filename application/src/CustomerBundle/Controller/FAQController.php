<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FAQController extends Controller
{
    public function indexAction()
    {
        return $this->render('CustomerBundle:FAQ:index.html.twig');
    }
}
