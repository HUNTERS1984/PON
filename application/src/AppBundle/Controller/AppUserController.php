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
use OAuth2\OAuth2ServerException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CoreBundle\Utils\Response as BaseResponse;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

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
     *     401="Returned when the user is not authorized"
     *   }
     * )
     * @View(serializerGroups={"view"}, serializerEnableMaxDepthChecks=true)
     * @Post("/signup", name="app_signup")
     * @return Response
     */
    public function postSingUpAction(Request $request)
    {
        $data = $request->request->all();
        $data['plainPassword'] = $data['password'];
        $form = $this->createForm(AppUserType::class, new AppUser());
        $form->submit($data);
        /**@var AppUser $appUser*/
        $appUser = $form->getData();
        $appUser->setPassword($data['password']);
        if($error = $this->get('pon.exception.exception_handler')->validate($appUser)) {
            return $this->view($error);
        }

        $this->getManager()->createAppUser($appUser);

        try {
            $request->request->set('username', $appUser->getUsername());
            $request->request->set('password', $data['plainPassword']);
            $request->request->set('client_id',$this->getParameter('client_id'));
            $request->request->set('client_secret',$this->getParameter('client_secret'));
            $request->request->set('grant_type',$this->getParameter('grant_type'));
            $token =  $this->get('fos_oauth_server.server')->grantAccessToken($request);

            $accessToken = json_decode($token->getContent(),true)['access_token'];
            $result['totken'] = $accessToken;
            return  $this->view(BaseResponse::getData($result));
        } catch (OAuth2ServerException $e) {
            $content = json_decode($e->getHttpResponse()->getContent());
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found', $content->error_description
            ));
        }
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
     *      }
     *  },
     *  statusCodes = {
     *     201 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   }
     * )
     * @View(serializerGroups={"view"}, serializerEnableMaxDepthChecks=true)
     * @Post("/signin", name="app_sigin")
     * @return Response
     */
    public function postSingInAction(Request $request)
    {
        try {
            $request->request->set('client_id',$this->getParameter('client_id'));
            $request->request->set('client_secret',$this->getParameter('client_secret'));
            $request->request->set('grant_type',$this->getParameter('grant_type'));
            $token =  $this->get('fos_oauth_server.server')->grantAccessToken($request);
        } catch (OAuth2ServerException $e) {
            $content = json_decode($e->getHttpResponse()->getContent());
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found', $content->error_description
            ));
        }

        /**@var AppUser $appUser*/
        $appUser = $this->getManager()->findOneBy(['username' => $request->get('username')]);
        $appUser->setBasePath($request->getSchemeAndHttpHost());
        if(!$appUser || !is_null($appUser->getDeletedAt())) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found'
            ));
        }

        $result = [
          'token' => json_decode($token->getContent(),true)['access_token'],
          'user' =>  $appUser
        ];

        return  $this->view(BaseResponse::getData($result));
    }

    /**
     * Update Profile
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to update app user",
     *  requirements={
     *      {
     *          "name"="username",
     *          "dataType"="string",
     *          "description"="username of app user"
     *      },
     *     {
     *          "name"="email",
     *          "dataType"="string",
     *          "description"="email of app user"
     *      },
     *     {
     *          "name"="gender",
     *          "dataType"="integer",
     *          "description"="gender of app user"
     *      },
     *     {
     *          "name"="address",
     *          "dataType"="string",
     *          "description"="address of app user"
     *      }
     *  },
     *  parameters={
     *      {"name"="avatar_url", "dataType"="file", "required"=true, "description"="image of avatar"}
     *  },
     *  headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The App User is not found"
     *   }
     * )
     * @View(serializerGroups={"view"}, serializerEnableMaxDepthChecks=true)
     * @Post("/profile", name="update_profile")
     * @return Response
     */
    public function postProfileAction(Request $request)
    {
        $manager = $this->getManager();
        /**@var AppUser $appUser*/
        $appUser = $this->getUser();
        $fileUpload = null;
        foreach ($_FILES as $file) {
            if($file['size'] <= 0){
                break;
            }
            $fileUpload = new UploadedFile($file['tmp_name'],
                $file['name'], $file['type'],
                $file['size'], $file['error'], $test = false);
            break;
        }
        $appUser->setBasePath($request->getSchemeAndHttpHost());
        $appUser = $this->get('pon.utils.data')->setData($request->request->all(), $appUser);

        if($fileUpload) {
            $appUser->setFile($fileUpload);
        }

        if($error =  $this->get('pon.exception.exception_handler')->validate($appUser)) {
            return $error;
        }

        if($fileUpload) {
            $appUser->upload();
        }


        $this->getManager()->saveAppUser($appUser);
        return $this->view(BaseResponse::getData($appUser), 200);
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
     *     401="Returned when the user is not authorized"
     *   }
     * )
     * @Get("/signout", name="sign_out")
     * @return Response
     */
    public function getSignOutAction(Request $request)
    {
        $appUser = $this->getUser();

        if(!$appUser) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found'
            ));
        }

        $token = $this->get('security.token_storage')->getToken();
        $accessToken = $this->get('fos_oauth_server.access_token_manager')->findTokenBy(['token'=> $token->getToken()]);
        $this->get('fos_oauth_server.access_token_manager')->deleteToken($accessToken);

        return $this->view(BaseResponse::getData([]));
    }

    /**
     * Check Valid Token
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
     *     401="Returned when the user is not authorized"
     *   }
     * )
     * @Get("/authorized", name="authorized")
     * @return Response
     */
    public function getCheckValidTokenAction(Request $request)
    {
        return $this->view(BaseResponse::getData([]));
    }

    /**
     * Check Profile
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to get profile",
     *     headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *     },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   }
     * )
     * @Get("/profile", name="authorized")
     * @View(serializerGroups={"view"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getProfileAction(Request $request)
    {
        /**@var AppUser $appUser*/
        $appUser = $this->getUser();
        $appUser->setBasePath($request->getSchemeAndHttpHost());
        return $this->view(BaseResponse::getData($appUser));
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
