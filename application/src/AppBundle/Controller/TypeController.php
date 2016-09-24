<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\Store;
use CoreBundle\Entity\StoreType;
use CoreBundle\Exception\ExceptionHandler;
use CoreBundle\Form\Type\StoreTypeType;
use CoreBundle\Manager\StoreTypeManager;
use CoreBundle\Serializator\Serializer;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class TypeController extends FOSRestController implements ClassResourceInterface
{

    /**
     * @return StoreTypeManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.store_type');
    }

    /**
     * @return Serializer
    */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }
}
