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
        if($error = $this->get('pon.exception.exception_handler')->validate($appUser)) {
            return $error;
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
            return $this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found', $content->error_description
            );
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
            return $this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found', $content->error_description
            );
        }


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
            return $this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found'
            );
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
