<?php

namespace CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller
{
    public function indexAction()
    {
        return $this->render('CustomerBundle:Contact:index.html.twig');
    }
}
