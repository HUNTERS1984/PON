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
     * Create User
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to create store type",
     *  input={
     *       "class"="CoreBundle\Entity\User",
     *       "groups"={"create_user"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\User",
     *       "groups"={"view"}
     *     },
     *  statusCodes = {
     *     201 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input"
     *   }
     * )
     * @return Response
     */
    public function postAction(Request $request)
    {
        $data = $request->request->all();
        $form = $this->createForm(UserType::class, new User());
        $form->submit($data);
        /**@var User $user*/
        $user = $form->getData();
        $this->get('pon.exception.exception_handler')->validate($user, BadRequestHttpException::class);
        $user = $this->getManager()->createUser($user);
        return $this->view($user, 201);
    }

    /**
     * Update User
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to update user",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of user"
     *      }
     *  },
     *  input={
     *       "class"="CoreBundle\Entity\User",
     *       "groups"={"create_user"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\User",
     *       "groups"={"view"}
     *     },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Store Type is not found"
     *   }
     * )
     * @return Response
     */
    public function putAction($id ,Request $request)
    {
        $manager = $this->getManager();
        /**@var User $user*/
        $user = $manager->findOneById($id);
        if(!$user) {
            $this->get('pon.exception.exception_handler')->throwError(
                'user.not_found',
                NotFoundHttpException::class
            );
        }

        $user = $this->get('pon.utils.data')->setData($request->request->all(), $user);

        $this->get('pon.exception.exception_handler')->validate($user, BadRequestHttpException::class);

        $user = $this->getManager()->saveUser($user);
        return $this->view($user, 200);
    }

    /**
     * Delete User
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to update user",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of user"
     *      }
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The User is not found"
     *   }
     * )
     * @return Response
     */
    public function deleteAction($id)
    {
        $manager = $this->getManager();
        $user = $manager->findOneById($id);
        if(!$user) {
            $this->get('pon.exception.exception_handler')->throwError(
                'user.not_found',
                NotFoundHttpException::class
            );
        }

        $status = $manager->deleteUser($user);
        if(!$status) {
            $this->get('pon.exception.exception_handler')->throwError(
                'user.delete_false',
                BadRequestHttpException::class
            );
        }

        return $this->view(null, 200);
    }

    /**
     * View Detail User
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to view user",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of user"
     *      }
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\User",
     *     "groups"={"view"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The User is not found"
     *   }
     * )
     * @return Response
     */
    public function getAction($id)
    {
        $manager = $this->getManager();
        /**@var User $user*/
        $user = $manager->findOneById($id);
        if(!$user || !is_null($user->getDeletedAt())) {
            $this->get('pon.exception.exception_handler')->throwError(
                'user.not_found',
                NotFoundHttpException::class
            );
        }

        $data = $this->getSerializer()->serialize($user, ['view']);

        return $this->view($data, 200);
    }

    /**
     * Get List User
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list user",
     *  parameters={
     *      {"name"="limit", "dataType"="integer", "required"=false, "description"="how many store types to return"},
     *      {"name"="offset", "dataType"="integer", "required"=false, "description"="point of store types to return"},
     *      {"name"="userName", "dataType"="string", "required"=false, "description"="username of user"}
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\User",
     *     "groups"={"view"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The User is not found"
     *   }
     * )
     * @return Response
     */
    public function cgetAction(Request $request)
    {
        $params = $request->query->all();
        $data = $this->getManager()->listUser($params);
        $users = $this->getSerializer()->serialize($data['data'], ['view']);
        return $this->view($users, 200, $data['pagination']);
    }

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
