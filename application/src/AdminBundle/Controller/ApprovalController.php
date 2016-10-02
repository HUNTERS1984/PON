<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ApprovalController extends Controller
{
    public function indexAction()
    {
        return $this->render('AdminBundle:Approval:index.html.twig');
    }
}
