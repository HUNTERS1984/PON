<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\News;
use CoreBundle\Entity\Store;
use CoreBundle\Form\Type\NewsType;
use CoreBundle\Manager\NewsManager;
use CoreBundle\Manager\StoreManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class NewsController extends FOSRestController  implements ClassResourceInterface
{

    /**
     * @return NewsManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.news');
    }

    /**
     * @return StoreManager
     */
    public function getStoreManager()
    {
        return $this->get('pon.manager.store');
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }
}
