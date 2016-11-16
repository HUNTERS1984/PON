<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CouponController extends Controller
{
    public function linkAction($id)
    {
        return $this->render('AdminBundle:Push:index.html.twig');
    }
}
