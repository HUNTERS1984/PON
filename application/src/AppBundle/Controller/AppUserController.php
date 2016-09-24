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
use FOS\RestBundle\Controller\Annotations\View;
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
     * SignUp
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to signup",
     *  requirements={
     *      {
     *          "name"="username",
     *          "dataType"="string",
     *          "description"="username of app"
     *      },
     *     {
     *          "name"="email",
     *          "dataType"="string",
     *          "description"="email of app"
     *      },
     *     {
     *          "name"="password",
     *          "dataType"="string",
     *          "description"="password of app"
     *      }
     *  },
     *  statusCodes = {
     *     201 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input"
     *   }
     * )
     * @View(serializerGroups={"view"}, serializerEnableMaxDepthChecks=true)
     * @Post("/signup", name="app_signup")
     * @return Response
     */
    public function postSingUpAction(Request $request)
    {
        $data = $request->request->all();
        $form = $this->createForm(AppUserType::class, new AppUser());
        $form->submit($data);
        /**@var AppUser $appUser*/
        $appUser = $form->getData();
        if($error = $this->get('pon.exception.exception_handler')->validate($appUser)) {
            return $error;
        }

        $result = $this->getManager()->createAppUser($appUser);

        return $this->view(BaseResponse::getData($result));
    }

    /**
     * SignIn
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to sigin",
     *  requirements={
     *      {
     *          "name"="username",
     *          "dataType"="string",
     *          "description"="username of app"
     *      },
     *     {
     *          "name"="password",
     *          "dataType"="string",
     *          "description"="password of app"
     *      },
     *     {
     *          "name"="grant_type",
     *          "dataType"="string",
     *          "description"="grand type of app"
     *      },
     *     {
     *          "name"="client_id",
     *          "dataType"="string",
     *          "description"="client id of app"
     *      },
     *     {
     *          "name"="client_secret",
     *          "dataType"="string",
     *          "description"="client id of app"
     *      }
     *  },
     *  statusCodes = {
     *     201 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input"
     *   }
     * )
     * @View(serializerGroups={"view"}, serializerEnableMaxDepthChecks=true)
     * @Post("/signin", name="app_sigin")
     * @return Response
     */
    public function postSingInAction(Request $request)
    {
        $token =  $this->get('fos_oauth_server.server')->grantAccessToken($request);
        $appUser = $this->getManager()->findOneBy(['username' => $request->get('username')]);

        if(!$appUser || !is_null($appUser->getDeletedAt())) {
            return $this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found'
            );
        }

        $result = [
          'token' => json_decode($token->getContent(),true)['access_token'],
          'user' =>  $appUser
        ];

        return  $this->view(BaseResponse::getData($result));
    }

    /**
     * SignOut
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to signout",
     *     headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *     },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The App User is not found"
     *   }
     * )
     * @Get("/signout", name="sign_out")
     * @return Response
     */
    public function getSignOutAction()
    {
        $appUser = $this->getUser();
        var_dump($appUser);die();
        if(!$appUser) {
            return $this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found'
            );
        }

       $this->get('security.token_storage')->setToken(null);

        return $this->view(BaseResponse::getData([]));
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
