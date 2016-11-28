<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\SocialProfile;
use CoreBundle\Exception\ExceptionHandler;
use CoreBundle\Form\Type\AppUserType;
use CoreBundle\Manager\AppUserManager;
use CoreBundle\Manager\SocialProfileManager;
use CoreBundle\Serializator\Serializer;
use Doctrine\Common\Inflector\Inflector;
use Facebook\FacebookResponse;
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
     *  section="User",
     *  resource=false,
     *  description="This api is used to signup (DONE)",
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
     *   },
     *   views = { "app"}
     * )
     * @View(serializerGroups={"view"}, serializerEnableMaxDepthChecks=true)
     * @Post("/signup", name="app_signup")
     * @return Response
     */
    public function postSingUpAction(Request $request)
    {
        $data = $request->request->all();
        $appUser = new AppUser();
        $appUser->setRoles(['ROLE_APP']);
        $appUser->setUsername($data['username']);
        $appUser->setPlainPassword($data['password']);
        $appUser->setEmail($data['email']);
        $appUser->setPassword($data['password']);
        if($error = $this->get('pon.exception.exception_handler')->validate($appUser)) {
            return $this->view($error);
        }

        $this->getManager()->createAppUser($appUser);

        try {
            $request->request->set('username', $appUser->getUsername());
            $request->request->set('password', $data['password']);
            $request->request->set('client_id',$this->getParameter('client_id'));
            $request->request->set('client_secret',$this->getParameter('client_secret'));
            $request->request->set('grant_type',$this->getParameter('grant_type'));
            $token =  $this->get('fos_oauth_server.server')->grantAccessToken($request);

            $accessToken = json_decode($token->getContent(),true)['access_token'];
            $result['token'] = $accessToken;
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
     *  section="User",
     *  resource=false,
     *  description="This api is used to sigin (DONE)",
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
     *   },
     *  views = { "app", "client"}
     * )
     * @View(serializerGroups={"view"}, serializerEnableMaxDepthChecks=true)
     * @Post("/signin", name="app_signin")
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
     * Facebook SignIn
     * @ApiDoc(
     *  section="User",
     *  resource=false,
     *  description="This api is used to signin (DONE)",
     *  requirements={
     *      {
     *          "name"="facebook_access_token",
     *          "dataType"="string",
     *          "description"="access_token of facebook"
     *      }
     *  },
     *  statusCodes = {
     *     201 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app"}
     * )
     * @View(serializerGroups={"view"}, serializerEnableMaxDepthChecks=true)
     * @Post("/facebook/signin", name="app_facebook_signin")
     * @return Response
     */
    public function postFacebookSingInAction(Request $request)
    {
        $accessToken = $request->get('facebook_access_token');
        $result = $this->getManager()->facebookLogin($accessToken);

        if(!$result['status']) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found', $result['message']
            ));
        }
        try {
            $request->request->set('username', $result['username']);
            $request->request->set('password', $result['password']);
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
     * Facebook Update Token
     * @ApiDoc(
     *  section="User",
     *  resource=false,
     *  description="This api is used to update token (DONE)",
     *  requirements={
     *      {
     *          "name"="facebook_access_token",
     *          "dataType"="string",
     *          "description"="access_token of facebook"
     *      }
     *  },
     *  statusCodes = {
     *     201 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *  headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *  },
     *   views = { "app"}
     * )
     * @View(serializerGroups={"view"}, serializerEnableMaxDepthChecks=true)
     * @Post("/facebook/token", name="app_facebook_update_token")
     * @return Response
     */
    public function postFacebookUpdateTokenAction(Request $request)
    {
        $accessToken = $request->get('facebook_access_token');
        /** @var AppUser $user */
        $user = $this->getUser();
        $result = $this->getManager()->getFacebookAccess($accessToken);
        if(!$result['status']) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'app_user.facebook.access_token.not_found', $result['message']
            ));
        }

        /** @var FacebookResponse $response */
        $response = $result['response'];
        $facebookUser = $response->getGraphUser();

        /** @var SocialProfile $socialProfile */
        $socialProfile = $this->getSocialProfileManager()->getSocialProfile($user, 1);

        if(!$user->getFacebookId()) {
            $user->setFacebookId($facebookUser->getId());
        }

        if(!$socialProfile) {
            $socialProfile = new SocialProfile();
            $socialProfile
                ->setSocialType(1)
                ->setAppUser($user)
                ->setSocialId($facebookUser->getId())
                ->setSocialToken($accessToken);
            $this->getSocialProfileManager()->createSocialProfile($socialProfile);
        }else{
            $this->getSocialProfileManager()->saveSocialProfile($socialProfile);
        }

        return  $this->view(BaseResponse::getData($result));
    }

    /**
     * Twitter SignIn
     * @ApiDoc(
     *  section="User",
     *  resource=false,
     *  description="This api is used to signin (DONE)",
     *  requirements={
     *      {
     *          "name"="twitter_access_token",
     *          "dataType"="string",
     *          "description"="authToken of facebook"
     *      },
     *     {
     *          "name"="twitter_access_token_secret",
     *          "dataType"="string",
     *          "description"="authTokenSecret of twitter"
     *      }
     *  },
     *  statusCodes = {
     *     201 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app"}
     * )
     * @View(serializerGroups={"view"}, serializerEnableMaxDepthChecks=true)
     * @Post("/twitter/signin", name="app_twitter_signin")
     * @return Response
     */
    public function postTwitterSingInAction(Request $request)
    {
        $accessToken = $request->get('twitter_access_token');
        $accessTokenSecret = $request->get('twitter_access_token_secret');
        $result = $this->getManager()->twitterLogin($accessToken, $accessTokenSecret);

        if(!$result['status']) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found', $result['message']
            ));
        }
        try {
            $request->request->set('username', $result['username']);
            $request->request->set('password', $result['password']);
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
     * Twitter Update Token
     * @ApiDoc(
     *  section="User",
     *  resource=false,
     *  description="This api is used to update token (DONE)",
     *  requirements={
     *      {
     *          "name"="twitter_access_token",
     *          "dataType"="string",
     *          "description"="authToken of facebook"
     *      },
     *     {
     *          "name"="twitter_access_token_secret",
     *          "dataType"="string",
     *          "description"="authTokenSecret of twitter"
     *      }
     *  },
     *  headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *  },
     *  statusCodes = {
     *     201 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app"}
     * )
     * @View(serializerGroups={"view"}, serializerEnableMaxDepthChecks=true)
     * @Post("/twitter/token", name="app_twitter_update_token")
     * @return Response
     */
    public function postTwitterUpdateTokenAction(Request $request)
    {
        $accessToken = $request->get('twitter_access_token');
        $accessTokenSecret = $request->get('twitter_access_token_secret');

        /** @var AppUser $user */
        $user = $this->getUser();
        $result = $this->getManager()->getTwitterAccess($accessToken, $accessTokenSecret);
        if(!$result['status']) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'app_user.twitter.access_token.not_found', $result['message']
            ));
        }

        /** @var FacebookResponse $response */
        $twitter = $result['response'];

        /** @var SocialProfile $socialProfile */
        $socialProfile = $this->getSocialProfileManager()->getSocialProfile($user, 2);

        if(!$user->getTwitterId()) {
            $user->setTwitterId($twitter->id);
        }

        if(!$socialProfile) {
            $socialProfile = new SocialProfile();
            $socialProfile
                ->setSocialType(2)
                ->setAppUser($user)
                ->setSocialId($twitter->id)
                ->setSocialToken($accessToken)
                ->setSocialSecret($accessTokenSecret);
            $this->getSocialProfileManager()->createSocialProfile($socialProfile);
        }else{
            $this->getSocialProfileManager()->saveSocialProfile($socialProfile);
        }

        return  $this->view(BaseResponse::getData($result));
    }

    /**
     * Update Profile
     * @ApiDoc(
     *  section="User",
     *  resource=false,
     *  description="This api is used to update app user (DONE)",
     *  requirements={
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "description"="name of app user"
     *      },
     *      {
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
     *   },
     *   views = { "app", "client"}
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
     *  section="User",
     *  resource=false,
     *  description="This api is used to signout (DONE)",
     *     headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *     },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *  views = { "app", "client"}
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
     *  section="User",
     *  resource=false,
     *  description="This api is used to signout (DONE)",
     *     headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *     },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app", "client"}
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
     *  section="User",
     *  resource=false,
     *  description="This api is used to get profile (DONE)",
     *     headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *     },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app", "client"}
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
     * @return SocialProfileManager
     */
    public function getSocialProfileManager()
    {
        return $this->get('pon.manager.social_profile');
    }

    /**
     * @return Serializer
    */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }
}
