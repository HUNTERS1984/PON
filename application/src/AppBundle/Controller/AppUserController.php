<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\AppUser;
use CoreBundle\Exception\ExceptionHandler;
use CoreBundle\Form\Type\AppUserType;
use CoreBundle\Manager\AppUserManager;
use CoreBundle\Serializator\Serializer;
use Doctrine\Common\Inflector\Inflector;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CoreBundle\Utils\Response as BaseResponse;

class AppUserController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Signup
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to signup",
     *  input={
     *       "class"="CoreBundle\Entity\AppUser",
     *       "groups"={"create_app_user"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\AppUser",
     *       "groups"={"view"}
     *     },
     *  statusCodes = {
     *     201 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input"
     *   }
     * )
     * @Post("/app/signup", name="create_app_user")
     * @return Response
     */
    public function postAction(Request $request)
    {
        $data = $request->request->all();
        $form = $this->createForm(AppUserType::class, new AppUser());
        $form->submit($data);
        /**@var AppUser $appUser*/
        $appUser = $form->getData();
        $this->get('pon.exception.exception_handler')->validate($appUser, BadRequestHttpException::class);
        $result = $this->getManager()->createAppUser($appUser);
        $data = array(
            "token"     =>  $result->getAccessTokens(),
            "id"        =>  $result->getId(),
            "profile"   =>  array()
        );
        return $this->view(BaseResponse::getData($data));
    }

    /**
     * Update App User
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to update app user",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of app user"
     *      }
     *  },
     *  input={
     *       "class"="CoreBundle\Entity\AppUser",
     *       "groups"={"create_app_user"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\AppUser",
     *       "groups"={"view"}
     *     },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The App User is not found"
     *   }
     * )
     * @Put("/app/users/{id}", name="update_app_user")
     * @return Response
     */
    public function putAction($id ,Request $request)
    {
        $manager = $this->getManager();
        /**@var AppUser $appUser*/
        $appUser = $manager->findOneById($id);
        if(!$appUser) {
            $this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found',
                NotFoundHttpException::class
            );
        }

        $appUser = $this->get('pon.utils.data')->setData($request->request->all(), $appUser);
        $this->get('pon.exception.exception_handler')->validate($appUser, BadRequestHttpException::class);

        $this->getManager()->saveAppUser($appUser);
        return $this->view($appUser, 200);
    }

    /**
     * Delete App User
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to update app user",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of app user"
     *      }
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The App User is not found"
     *   }
     * )
     * @Delete("/app/users/{id}", name="delete_app_user")
     * @return Response
     */
    public function deleteAction($id)
    {
        $manager = $this->getManager();
        $appUser = $manager->findOneById($id);
        if(!$appUser) {
            $this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found',
                NotFoundHttpException::class
            );
        }

        $status = $manager->deleteAppUser($appUser);
        if(!$status) {
            $this->get('pon.exception.exception_handler')->throwError(
                'app_user.delete_false',
                BadRequestHttpException::class
            );
        }

        return $this->view(null, 200);
    }

    /**
     * View Detail App User
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to view app user",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of app user"
     *      }
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\AppUser",
     *     "groups"={"view"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The App User is not found"
     *   }
     * )
     * @Get("/app/users/{id}", name="view_user")
     * @return Response
     */
    public function getAction($id)
    {
        $manager = $this->getManager();
        /**@var AppUser $appUser*/
        $appUser = $manager->findOneById($id);
        if(!$appUser || !is_null($appUser->getDeletedAt())) {
            $this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found',
                NotFoundHttpException::class
            );
        }

        $data = $this->getSerializer()->serialize($appUser, ['view']);

        return $this->view($data, 200);
    }

    /**
     * Get List App User
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list app user",
     *  parameters={
     *      {"name"="limit", "dataType"="integer", "required"=false, "description"="how many store types to return"},
     *      {"name"="offset", "dataType"="integer", "required"=false, "description"="point of store types to return"},
     *      {"name"="userName", "dataType"="string", "required"=false, "description"="username of app user"}
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\AppUser",
     *     "groups"={"view"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The App User is not found"
     *   }
     * )
     * @Get("/app/users", name="list_user")
     * @return Response
     */
    public function cgetAction(Request $request)
    {
        $params = $request->query->all();
        $data = $this->getManager()->listAppUser($params);
        $appUsers = $this->getSerializer()->serialize($data['data'], ['view']);
        return $this->view($appUsers, 200, $data['pagination']);
    }

    /**
     * @return AppUserManager
     */
    public function getManager()
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
