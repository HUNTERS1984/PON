<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Post;
use CoreBundle\Form\Type\PostType;
use CoreBundle\Manager\AppUserManager;
use CoreBundle\Manager\PostManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class PostController extends FOSRestController  implements ClassResourceInterface
{

    /**
     * @return PostManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.post');
    }

    /**
     * @return AppUserManager
     */
    public function getAppUserManager()
    {
        return $this->get('pon.manager.app_user');
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }
}
