<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\UserType;
use CoreBundle\Entity\User;
use CoreBundle\Manager\UserManager;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class UserController extends FOSRestController  implements ClassResourceInterface
{
    /**
     * CreateUser
     * @ApiDoc(
     *  resource=true,
     *  description="Home page",
     *  requirements={
     *      {
     *          "name"="username",
     *          "dataType"="string",
     *          "description"="Username"
     *      },
     *     {
     *          "name"="email",
     *          "dataType"="string",
     *          "description"="Email"
     *      },
     *     {
     *          "name"="password",
     *          "dataType"="string",
     *          "description"="Password"
     *      }
     *  }
     * )
     * @return array
     */
    public function postAction(Request $request)
    {
        $data = $request->request->all();

        $user = new User();
        $user
            ->setUsername($data['user_name'])
            ->setEmail($data['email'])
            ->setPassword($data['password']);

        $errors = $this->get('validator')->validate($user);
        if(count($errors) > 0) {
            throw new BadRequestHttpException($this->get('jms_serializer')->serialize($errors, 'json'));
        }

        /**@var UserManager $manager */
        $manager = $this->get('pon.manager.user');
        $manager->saveUser($user);

        return ['status'=> true];

    }

    /**
     * UpdateUser
     * @ApiDoc(
     *  resource=true,
     *  description="Home page",
     *  requirements={
     *      {
     *          "name"="username",
     *          "dataType"="string",
     *          "description"="Username"
     *      },
     *     {
     *          "name"="email",
     *          "dataType"="string",
     *          "description"="Email"
     *      },
     *     {
     *          "name"="password",
     *          "dataType"="string",
     *          "description"="Password"
     *      }
     *  }
     * )
     * @return array
     */
    public function putAction($id, Request $request)
    {

        /**@var UserManager $manager */
        $manager = $this->get('pon.manager.user');
        $user  = $manager->findOneById($id);
        if(!$user) {
            throw new NotFoundHttpException("User Not found");
        }

        $data = $request->request->all();

        $user
            ->setUsername($data['user_name'])
            ->setEmail($data['email'])
            ->setPassword($data['password']);

        $errors = $this->get('validator')->validate($user);
        if(count($errors) > 0) {
            throw new BadRequestHttpException($this->get('jms_serializer')->serialize($errors, 'json'));
        }


        $manager->saveUser($user);

        return ['status'=> true];

    }

    /**
     * View User
     * @ApiDoc(
     *  resource=true,
     *  description="View User",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "description"="Id of User",
     *          "requirement"="\d+",
     *      }
     *  }
     * )
     * @return array
     */
    public function getAction($id)
    {
        /**@var UserManager $manager */
        $manager = $this->get('pon.manager.user');
        $user = $manager->getUser($id);

        if(!$user) {
            throw new NotFoundHttpException("User Not found!");
        }

        return ['status'=> true, 'data' => $user];

    }

    /**
     * Delete user
     * @ApiDoc(
     *  resource=true,
     *  description="Delete user",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "description"="Id of User",
     *          "requirement"="\d+",
     *      }
     *  }
     * )
     * @return array
     */
    public function deleteAction($id)
    {
        /**@var UserManager $manager */
        $manager = $this->get('pon.manager.user');
        $user = $manager->getUser($id);

        if(!$user) {
            throw new NotFoundHttpException("User Not found!");
        }
        
        $manager->deletUser($user);

        return ['status'=> true];

    }

    /**
     * Get List User
     * @ApiDoc(
     *  resource=true,
     *  description="Home page"
     * )
     * @return array
     */
    public function cgetAction(Request $request)
    {
        /**@var UserManager $manager */
        $manager = $this->get('pon.manager.user');
        $users = $manager->getUsers();


        return ['status' => true, 'data' => $users];
    }
    
}
