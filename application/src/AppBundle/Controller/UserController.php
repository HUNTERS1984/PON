<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\User;
use CoreBundle\Exception\ExceptionHandler;
use CoreBundle\Form\Type\StoreTypeType;
use CoreBundle\Form\Type\UserType;
use CoreBundle\Manager\StoreTypeManager;
use CoreBundle\Manager\UserManager;
use CoreBundle\Serializator\Serializer;
use Doctrine\Common\Inflector\Inflector;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class UserController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @return UserManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.user');
    }

    /**
     * @return Serializer
    */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }
}
