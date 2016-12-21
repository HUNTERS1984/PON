<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\Coupon;
use CoreBundle\Manager\AppUserManager;
use CoreBundle\Manager\CouponManager;
use CoreBundle\Manager\CategoryManager;
use CoreBundle\Manager\LikeListManager;
use CoreBundle\Manager\UseListManager;
use Faker\Factory;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CoreBundle\Utils\Response as BaseResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class CouponController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get List Featured Category
     * @ApiDoc(
     *  section="Coupon",
     *  resource=false,
     *  description="This api is used to list coupon (DONE)",
     *  requirements={
     *      {
     *          "name"="type",
     *          "dataType"="integer",
     *          "description"="type (1,2,3,4)"
     *      }
     *  },
     *  headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *     },
     *  parameters={
     *      {"name"="latitude", "dataType"="string", "required"=false, "description"="latitude of user"},
     *      {"name"="longitude", "dataType"="string", "required"=false, "description"="longitude of user"},
     *      {"name"="page_size", "dataType"="integer", "required"=false, "description"="page size to return"},
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app"}
     * )
     * @Get("/featured/{type}/coupons")
     * @View(serializerGroups={"featured_coupon"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getFeaturedCouponAction($type, Request $request)
    {
        $params = $request->query->all();
        if ($type == 3 && (empty($params['latitude']) || empty($params['longitude']))) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_blank.latitude_longitude'
            ));
        }

        $user = $this->getUser();

        $result = $this->getManager()->getFeaturedCoupon($type, $params, $user);
        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));
    }

    /**
     * Get List Featured Coupons By Category
     * @ApiDoc(
     *  section="Coupon",
     *  resource=false,
     *  description="This api is used to list coupon (DONE)",
     *  requirements={
     *      {
     *          "name"="type",
     *          "dataType"="integer",
     *          "description"="featured type (1,2,3,4)"
     *      },
     *     {
     *          "name"="category",
     *          "dataType"="integer",
     *          "description"="id of category"
     *      }
     *  },
     *  headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *     },
     *  parameters={
     *     {"name"="latitude", "dataType"="string", "required"=false, "description"="latitude of user"},
     *      {"name"="longitude", "dataType"="string", "required"=false, "description"="longitude of user"},
     *      {"name"="page_size", "dataType"="integer", "required"=false, "description"="page size to return"},
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app"}
     * )
     * @Get("/featured/{type}/category/{category}/coupons")
     * @View(serializerGroups={"featured_coupon"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getCouponsByFeaturedAndCategoryAction($type, $category, Request $request)
    {
        $params = $request->query->all();

        if (!$categoryObject = $this->getCategoryManager()->getCategory($category)) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_found'
            ));
        }

        if ($type == 3 && (empty($params['latitude']) || empty($params['longitude']))) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_blank.latitude_longitude'
            ));
        }

        $user = $this->getUser();
        $result = $this->getManager()->getFullFeaturedCoupon($type, $categoryObject, $params, $user);

        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));

    }

    /**
     * View Detail Coupon
     * @ApiDoc(
     *  section="Coupon",
     *  resource=false,
     *  description="This api is used to view coupon (DONE)",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of coupon"
     *      }
     *  },
     *  headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *     },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app"}
     * )
     * @View(serializerGroups={"view_coupon","list_coupon_store"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getAction($id)
    {
        $coupon = $this->getManager()->getCoupon($id);
        if (!$coupon) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_found'
            ));
        }

        $coupon->setImpression($coupon->getImpression() + 1);

        $coupon = $this->getManager()->saveCoupon($coupon);

        return $this->view(BaseResponse::getData($coupon));
    }

    /**
     * Get List Favorite Coupon
     * @ApiDoc(
     *  section="Coupon",
     *  resource=false,
     *  description="This api is used to list coupon (DONE)",
     *  headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *     },
     *  parameters={
     *      {"name"="page_size", "dataType"="integer", "required"=false, "description"="page size to return"},
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"},
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app"}
     * )
     * @Get("/favorite/coupons")
     * @View(serializerGroups={"list_coupon","list_coupon_store"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getFavoriteCouponAction(Request $request)
    {
        $user = $this->getUser();
        $params = $request->query->all();
        $result = $this->getLikeListManager()->getFavoriteCoupons($user, $params);
        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));
    }

    /**
     * Get List Used Coupon
     * @ApiDoc(
     *  section="Coupon",
     *  resource=false,
     *  description="This api is used to list coupon (DONE)",
     *  headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *     },
     *  parameters={
     *      {"name"="page_size", "dataType"="integer", "required"=false, "description"="page size to return"},
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"},
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app"}
     * )
     * @Get("/used/coupons")
     * @View(serializerGroups={"list_coupon","list_coupon_store"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getUsedCouponAction(Request $request)
    {

        $user = $this->getUser();
        $params = $request->query->all();
        $result = $this->getUseListManager()->getUsedCoupons($user, $params);
        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));
    }

    /**
     * search coupon
     * @ApiDoc(
     *  section="Coupon",
     *  resource=false,
     *  description="This api is used to search coupon (DONE)",
     *  headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *     },
     *  parameters={
     *      {"name"="query", "dataType"="string", "required"=false, "description"="query string"},
     *      {"name"="page_size", "dataType"="integer", "required"=false, "description"="page size to return"},
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app"}
     * )
     * @Get("/search/coupons")
     * @View(serializerGroups={"list_coupon","list_coupon_store"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getSearchingCouponAction(Request $request)
    {
        $params = $request->query->all();
        $result = $this->getManager()->search($params);
        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));
    }

    /**
     * Like Coupon
     * @ApiDoc(
     *  section="Coupon",
     *  resource=false,
     *  description="This api is used to like coupon (DONE)",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="string",
     *          "description"="id of coupon"
     *      }
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
     *   views = { "app"}
     * )
     *
     * @Post("/like/coupons/{id}", name="like_coupon")
     * @return Response
     */
    public function postLikeCouponAction($id, Request $request)
    {
        $user = $this->getUser();
        $coupon = $this->getManager()->getCoupon($id);
        if (!$coupon) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_found'
            ));
        }

        $likeList = $this->getLikeListManager()->isLike($user, $coupon);
        if (!$likeList) {
            $coupon = $this->getLikeListManager()->likeCoupon($user, $coupon);
            if (!$coupon) {
                return $this->view($this->get('pon.exception.exception_handler')->throwError(
                    'coupon.like.not_success'
                ));
            }
        }

        return $this->view(BaseResponse::getData([]), 200);
    }

    /**
     * Like Coupon
     * @ApiDoc(
     *  section="Coupon",
     *  resource=false,
     *  description="This api is used to unlike coupon (DONE)",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="string",
     *          "description"="id of coupon"
     *      }
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
     *   views = { "app"}
     * )
     *
     * @Post("/unlike/coupons/{id}", name="unlike_coupon")
     * @return Response
     */
    public function postUnLikeCouponAction($id, Request $request)
    {
        $user = $this->getUser();
        $coupon = $this->getManager()->getCoupon($id);
        if (!$coupon) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_found'
            ));
        }

        $likeList = $this->getLikeListManager()->isLike($user, $coupon);
        if (!$likeList) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.like.not_exists'
            ));
        }

        $result = $this->getLikeListManager()->unLikeCoupon($likeList);
        if (!$result) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.unlike.not_success'
            ));
        }

        return $this->view(BaseResponse::getData([]), 200);
    }

    /**
     * Approve Coupon
     * @ApiDoc(
     *  section="Coupon",
     *  resource=false,
     *  description="This api is used to approve coupon (DONE)",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="string",
     *          "description"="id of coupon"
     *      },
     *     {
     *          "name"="username",
     *          "dataType"="string",
     *          "description"="username of app user"
     *      }
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
     *   views = { "client"}
     * )
     *
     * @Post("/approve/coupons/{id}", name="approve_coupon")
     * @Security("is_granted('ROLE_CLIENT')")
     * @return Response
     */
    public function postApproveCouponAction($id, Request $request)
    {
        $user = $this->getUser();
        $coupon = $this->getManager()->getCouponByCouponId($id);
        if (!$coupon) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_found'
            ));
        }

        $username = $request->request->get('username');
        $appUser = $this->getAppUserManager()->getAppUserByUsername($username);

        if (!$appUser) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found'
            ));
        }

        $useCoupon = $this->getUseListManager()->getUseCoupon($appUser, $coupon);

        if($useCoupon && !in_array($useCoupon->getStatus(), [0, 1])) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'use_list.coupon.ever_approved'
            ));
        }

        if($useCoupon && $useCoupon->getStatus() == 1) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'use_list.coupon.approved'
            ));
        }

        if(!$useCoupon) {
            // create new approve coupon
            $useCoupon = $this->getUseListManager()->createNewUseList($appUser, $coupon);
        }

        if(!$useCoupon) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'use_list.not_found'
            ));
        }

        $useCoupon = $this->getUseListManager()->approveCoupon($useCoupon);

        if(!$useCoupon) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'use_list.approve_not_success'
            ));
        }

        return $this->view(BaseResponse::getData([]), 200);
    }

    /**
     * Request Coupon
     * @ApiDoc(
     *  section="Coupon",
     *  resource=false,
     *  description="This api is used to request coupon",
     *  requirements={
     *      {
     *          "name"="code",
     *          "dataType"="string",
     *          "description"="QR Code of coupon"
     *      }
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
     *   views = { "app"}
     * )
     *
     * @Post("/request/coupons/{code}", name="request_coupon")
     * @return Response
     */
    public function postRequestCouponAction($code, Request $request)
    {
        $user = $this->getUser();
        $useCoupon = $this->getManager()->getCouponToRequest($user, $code);
        if (!$useCoupon) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_found'
            ));
        }

        $useCoupon = $this->getUseListManager()->requestCoupon($useCoupon);
        if (!$useCoupon) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.request.not_success'
            ));
        }

        return $this->view(BaseResponse::getData([]), 200);
    }

    /**
     * Get List Request Coupon
     * @ApiDoc(
     *  section="Coupon",
     *  resource=false,
     *  description="This api is used to list request coupon (DONE)",
     *  headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *     },
     *  parameters={
     *      {"name"="page_size", "dataType"="integer", "required"=false, "description"="page size to return"},
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"},
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "client"}
     * )
     * @Get("/request/coupons")
     * @Security("is_granted('ROLE_CLIENT')")
     * @View(serializerGroups={"use_list"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getRequestCouponAction(Request $request)
    {
        $params = $request->query->all();
        $result = $this->getManager()->listRequestCoupons($params);
        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));
    }

    /**
     * Get Request Coupon detail
     * @ApiDoc(
     *  section="Coupon",
     *  resource=false,
     *  description="This api is used to get request coupon detail (DONE)",
     *  headers={
     *         {
     *             "name"="Authorization",
     *             "description"="Bearer [token key]"
     *         }
     *     },
     *      requirements={
     *      {
     *          "name"="code",
     *          "dataType"="string",
     *          "description"="QR code of coupon"
     *      }
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "client"}
     * )
     * @Get("/request/coupons/{code}")
     * @Security("is_granted('ROLE_CLIENT')")
     * @View(serializerGroups={"use_list"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getRequestCouponDetailAction($code)
    {
        $data = $this->getManager()->getRequestCouponDetail($code);

        if (!$data) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_found'
            ));
        }

        return $this->view(BaseResponse::getData($data));
    }

    /**
     * Accept Coupon
     * @ApiDoc(
     *  section="Coupon",
     *  resource=false,
     *  description="This api is used to accept coupon (DONE)",
     *  requirements={
     *      {
     *          "name"="code",
     *          "dataType"="string",
     *          "description"="QR code of coupon"
     *      }
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
     *   views = { "client"}
     * )
     *
     * @Post("/accept/coupons/{code}", name="like_coupon")
     * @Security("is_granted('ROLE_CLIENT')")
     * @return Response
     */
    public function postAcceptCouponAction($code)
    {
        $useList = $this->getManager()->getRequestCouponDetail($code);

        if (!$useList) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_found'
            ));
        }

        $this->getUseListManager()->acceptCoupon($useList);
        return $this->view(BaseResponse::getData([]), 200);
    }

    /**
     * Decline Coupon
     * @ApiDoc(
     *  section="Coupon",
     *  resource=false,
     *  description="This api is used to decline coupon (DONE)",
     *  requirements={
     *      {
     *          "name"="code",
     *          "dataType"="string",
     *          "description"="QR code of coupon"
     *      }
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
     *   views = { "client"}
     * )
     *
     * @Post("/decline/coupons/{code}", name="like_coupon")
     * @Security("is_granted('ROLE_CLIENT')")
     * @return Response
     */
    public function postDeclineCouponAction($code, Request $request)
    {
        $useList = $this->getManager()->getRequestCouponDetail($code);

        if (!$useList) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_found'
            ));
        }

        $this->getUseListManager()->declineCoupon($useList);
        return $this->view(BaseResponse::getData([]), 200);
    }

    /**
     * Scrapping Data
     * @ApiDoc(
     *  section="Scrapping",
     *  resource=false,
     *  description="This api is used to scraping Data From SNS(DONE)",
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
     *   views = { "client"}
     * )
     *
     * @Get("/scrapping", name="scrapping")
     * @Security("is_granted('ROLE_CLIENT')")
     * @return Response
     */
    public function getScrappingCouponAction()
    {
        $kernel = $this->get('kernel');
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'pon:scrapping',
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);
        $content = $output->fetch();

        return $this->view(BaseResponse::getData(['message'=> $content]), 200);
    }

    /**
     * Sync Post Data
     * @ApiDoc(
     *  section="Scrapping",
     *  resource=false,
     *  description="This api is used to Sync Post Data From SNS(DONE)",
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
     *   views = { "client"}
     * )
     *
     * @Get("/sync", name="sync_post")
     * @Security("is_granted('ROLE_CLIENT')")
     * @return Response
     */
    public function getSyncCouponAction()
    {
        $kernel = $this->get('kernel');
        $application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $application->setAutoExit(false);

        $input = new ArrayInput([
            'command' => 'pon:sync',
        ]);

        $output = new BufferedOutput();
        $application->run($input, $output);
        $content = $output->fetch();

        return $this->view(BaseResponse::getData(['message'=> $content]), 200);
    }



    /**
     * @return CouponManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.coupon');
    }

    /**
     * @return AppUserManager
     */
    public function getAppUserManager()
    {
        return $this->get('pon.manager.app_user');
    }

    /**
     * @return CategoryManager
     */
    public function getCategoryManager()
    {
        return $this->get('pon.manager.category');
    }

    /**
     * @return UseListManager
     */
    public function getUseListManager()
    {
        return $this->get('pon.manager.use_list');
    }

    /**
     * @return LikeListManager
     */
    public function getLikeListManager()
    {
        return $this->get('pon.manager.like_list');
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }
}
