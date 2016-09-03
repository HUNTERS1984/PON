<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends FOSRestController  implements ClassResourceInterface
{
    /**
     * Home
     * @Get("/home", name = "home", options = {"method_prefix" = true})
     * @ApiDoc(
     *  resource=true,
     *  description="Home page"
     * )
     * @return array
     */
    public function cgetAction(Request $request)
    {
        $data = array("hello" => "world");
        $view = $this->view($data);
        return $this->handleView($view);
    }
}
