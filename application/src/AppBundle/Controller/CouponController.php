<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\LikeList;
use CoreBundle\Entity\UseList;
use CoreBundle\Form\Type\CouponType;
use CoreBundle\Form\Type\CouponTypeType;
use CoreBundle\Manager\CouponManager;
 
use CoreBundle\Manager\CouponTypeManager;
use CoreBundle\Manager\LikeListManager;
use CoreBundle\Manager\UseListManager;
 
use CoreBundle\Manager\CategoryManager;
 
use CoreBundle\Manager\StoreManager;
use Faker\Factory;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CoreBundle\Utils\Response as BaseResponse;


class CouponController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get List Feature Coupon
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list coupon",
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
     *      {"name"="page_size", "dataType"="integer", "required"=false, "description"="page size to return"},
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"},
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   }
     * )
     * @Get("/featured/{type}/coupons")
     * @return Response
     */
    public function getFeaturedCouponAction($type, Request $request)
    {
        $faker = Factory::create('ja_JP');
        $data = [];
        $j = 0;
        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'id' => $i + 1,
                'name' => $faker->name,
                'icon_url' => $faker->imageUrl(46, 46, 'food'),
                'coupons' => [
                    [
                        'id' => $j + 1,
                        'title' => $faker->name,
                        'image_url' => $faker->imageUrl(640, 480, 'food'),
                        'expired_time' => new \DateTime(),
                        'is_like' => $faker->randomElement([0, 1]),
                        'can_use' => $faker->randomElement([0, 1])
                    ],
                    [
                        'id' => $j + 2,
                        'title' => $faker->name,
                        'image_url' => $faker->imageUrl(640, 480, 'food'),
                        'expired_time' => new \DateTime(),
                        'is_like' => $faker->randomElement([0, 1]),
                        'can_use' => $faker->randomElement([0, 1]),
                    ],
                    [
                        'id' => $j + 3,
                        'title' => $faker->name,
                        'image_url' => $faker->imageUrl(640, 480, 'food'),
                        'expired_time' => new \DateTime(),
                        'is_like' => $faker->randomElement([0, 1]),
                        'can_use' => $faker->randomElement([0, 1]),
                    ],
                    [
                        'id' => $j + 4,
                        'title' => $faker->name,
                        'image_url' => $faker->imageUrl(640, 480, 'food'),
                        'expired_time' => new \DateTime(),
                        'is_like' => $faker->randomElement([0, 1]),
                        'can_use' => $faker->randomElement([0, 1]),
                    ],
                    [
                        'id' => $j + 5,
                        'title' => $faker->name,
                        'image_url' => $faker->imageUrl(640, 480, 'food'),
                        'expired_time' => new \DateTime(),
                        'is_like' => $faker->randomElement([0, 1]),
                        'can_use' => $faker->randomElement([0, 1]),
                    ],
                ]

            ];
            $j = $j + 5;
        }
        return $this->view(BaseResponse::getData($data, [
            'limit' => 20,
            'offset' => 0,
            'item_total' => 20,
            'page_total' => 1,
            'current_page' => 1
        ]));

    }

    /**
     * Get List Feature Coupon Follow Coupon Type
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list coupon",
     *  requirements={
     *      {
     *          "name"="type",
     *          "dataType"="integer",
     *          "description"="featured type (1,2,3,4)"
     *      },
     *     {
     *          "name"="couponType",
     *          "dataType"="integer",
     *          "description"="type of coupon"
     *      }
     *  },
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
     *   }
     * )
     * @Get("/featured/{type}/coupons/{couponType}")
     * @return Response
     */
    public function getCouponsByFeaturedAndTypeAction($type, $couponType, Request $request)
    {
        $faker = Factory::create('ja_JP');
        $data = [
            'id' => $couponType,
            'name' => $faker->name,
            'icon_url' => $faker->imageUrl(46, 46, 'food')
        ];
        $coupons = [];
        for ($i = 0; $i < 20; $i++) {
            $coupons[] =
                [
                    'id' => $i + 1,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => $faker->randomElement([0, 1]),
                    'can_use' => $faker->randomElement([0, 1])
                ];
        }
        $data['coupons'] = $coupons;
        return $this->view(BaseResponse::getData($data, [
            'limit' => 20,
            'offset' => 0,
            'item_total' => 20,
            'page_total' => 1,
            'current_page' => 1
        ]));

    }

    /**
     * View Detail Coupon
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to view coupon",
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
     *   }
     * )
     *
     * @return Response
     */
    public function getAction($id)
    {
        $userLoginId = 0;
        /**@var AppUser $appUser*/
        $appUser = $this->getUser();
        if($appUser){
            $userLoginId = $appUser->getId();
        }
        $manager = $this->getManager();
        /**@var Coupon $coupon*/
        $couponDetail = $manager->findOneById($id);
        if($couponDetail){
            $couponDetail = $this->getSerializer()->serialize($couponDetail, ['view_coupon']);
            $couponDetail["can_use"] = ($couponDetail["need_login"] == 0 || ($couponDetail["need_login"] == 1 && $userLoginId > 0)) ? 1 : 0;
            $couponDetail["is_like"] = 0;
            $couponDetail["shop"]["is_follow"] = 0;
            $listCoupon = $manager->listCoupon(array("page_size" => 4, "type" => $couponDetail["couponType"]));
            $listCouponId = [$id => (int)$id];
            $listCouponTypeValue = $this->getParameter('coupon_types');
            if(isset($listCoupon['data']) && count($listCoupon['data']) > 0){
                $listCoupon = $this->getSerializer()->serialize($listCoupon, ['view_coupon_list']);
                foreach ($listCoupon['data'] as &$coupon){
                    $coupon["is_like"] = 0;
                    $coupon["can_use"] = ($coupon["need_login"] == 0 || ($coupon["need_login"] == 1 && $userLoginId > 0)) ? 1 : 0;
                    $listCouponId[$coupon["id"]] = $coupon["id"];
                    $couponType = [
                        'id' => $coupon["couponType"],
                        'name' => $listCouponTypeValue[$coupon["couponType"]],
                    ];
                    $coupon["couponType"] = $couponType;
                }
            }
            $couponDetail["couponType"] = [
                'id' => $couponDetail["couponType"],
                'name' => $listCouponTypeValue[$couponDetail["couponType"]],
            ];
            if($userLoginId > 0){
                $managerLikeList = $this->getLikeListManager();
                $listLike = $managerLikeList->listCoupon(array("list_coupon_id" => $listCouponId, "app_user_id" => $userLoginId));
                $listLike = $this->getSerializer()->serialize($listLike, ['view_coupon_list']);
                if(isset($listLike['data']) && count($listLike["data"]) > 0){
                    $listLikeCouponId = [];
                    foreach ($listLike['data'] as $like){
                        $listLikeCouponId[] = $like["coupon"]["id"];
                    }
                    if(in_array($id, $listLikeCouponId)){
                        $couponDetail["is_like"] = 1;
                    }
                    foreach ($listCoupon['data'] as &$coupon){
                        if(in_array($coupon["id"], $listLikeCouponId)){
                            $coupon["is_like"] = 1;
                        }
                    }
                }
            }
            $couponDetail["similar_coupon"] = $listCoupon["data"];
        }

        return $this->view(BaseResponse::getData($couponDetail));

        $faker = Factory::create('ja_JP');
        $couponPhotoUrl = [];
        $userPhotoUrl = [];
        for($i=1; $i< $id; $i++) {
            $couponPhotoUrl[] = $faker->imageUrl(640, 480, 'food');
            $userPhotoUrl[] = $faker->imageUrl(640, 480, 'food');
        }

        $data = [
            'id' => (int)$id,
            'title' => $faker->name,
            'expired_time' => new \DateTime(),
            'image_url' => $faker->imageUrl(640, 480, 'food'),
            'is_like' => $faker->randomElement([0, 1]),
            'need_login' => $needLogin,
            'can_use' => $canUse,
            //'code' => $faker->ean13,
            'description' => $faker->paragraph(6),
            'shop' => [
                'id' => $faker->numberBetween(1, 200),
                'title' => $faker->company,
                'operation_start_time' =>  '08:00',
                'operation_end_time' => '23:00',
                'avatar_url' => $faker->imageUrl(640, 480, 'food'),
                //'is_follow' => $faker->randomElement([0, 1]),
                'tel' => $faker->phoneNumber,
                'lattitude' => '35.911594',
                'longitude' => '137.746582',
                'address' => $faker->address,
                'close_date' => "Saturday and Sunday",
                'ave_bill' => $faker->numberBetween(100, 200)
            ],
            'coupon_type' => [
                'id' => $faker->randomElement([0, 1]),
                'name' => $faker->name,
                'icon_url' => $faker->imageUrl(46, 46, 'food')
            ],
            //'coupon_photo_url' =>  $couponPhotoUrl,
            //'user_photo_url' => $userPhotoUrl,
            'similar_coupon' => [
                [
                    'id' => 1,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => $faker->randomElement([0, 1]),
                    'can_use' => $faker->randomElement([0, 1]),
                    'coupon_type' => [
                        'id' => $faker->randomElement([1, 2]),
                        'name' => $faker->name,
                    ],
                    'need_login' => 1
                ],
                [
                    'id' => 2,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => $faker->randomElement([0, 1]),
                    'can_use' => $faker->randomElement([0, 1])
                ],
                [
                    'id' => 3,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => $faker->randomElement([0, 1]),
                    'can_use' => $faker->randomElement([0, 1])
                ],
                [
                    'id' => 4,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => $faker->randomElement([0, 1]),
                    'can_use' => $faker->randomElement([0, 1])
                ],
            ],
        ];

        return $this->view(BaseResponse::getData($data));
    }

    /**
     * Get List Favorite Coupon
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list coupon",
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
     *   }
     * )
     * @Get("/favorite/coupons")
     * @View(serializerGroups={"view_coupon"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getFavoriteCouponAction(Request $request)
    {
        //$userLoginId = 1;
        //$pageSize = $request->request->get('page_size'); not work
        $pageSize = isset($_GET["page_size"]) ? $_GET["page_size"] : 10;
        //$pageIndex = $request->request->get('page_index'); not work
        $pageIndex = isset($_GET["page_index"]) ? $_GET["page_index"] : 1;
        /**@var AppUser $appUser*/
        $appUser = $this->getUser();

        if(!$appUser) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found'
            ) , 404);
        }

        if ($appUser){
            $userLoginId = $appUser->getId();
            if(!$userLoginId) {
                return $this->view($this->get('pon.exception.exception_handler')->throwError(
                    'app_user.not_found'
                ) , 404);
            }
        }
        $managerLikeList = $this->getLikeListManager();
        /**@var Coupon $coupon*/
        $listCouponTmp = $managerLikeList->listCoupon(array("page_size" => $pageSize, "page_index" => $pageIndex, "app_user_id" => $userLoginId));
        $listCouponTmp = $this->getSerializer()->serialize($listCouponTmp, ['view_coupon_list']);
        $listCoupon = [];
        if(!empty($listCouponTmp["data"])) {
            $listCoupon["pagination"] = $listCouponTmp["pagination"];
            foreach ($listCouponTmp['data'] as $k => $v) {
                $coupon = $v["coupon"];
                $listCoupon['data'][$k]['id'] = $coupon["id"];
                $listCoupon['data'][$k]['couponType'] = $coupon["couponType"];
                $listCoupon['data'][$k]['title'] = $coupon["title"];
                $listCoupon['data'][$k]['image_url'] = $coupon["image_url"];
                $listCoupon['data'][$k]['expired_time'] = $coupon["expired_time"];
                $listCoupon['data'][$k]['is_like'] = 1;
                $listCoupon['data'][$k]['can_use'] = 0;
                if ($coupon["status"] == 1) {
                    $listCoupon['data'][$k]['can_use'] = 1;
                }
            }
        }
        return $this->view($listCoupon);


        $faker = Factory::create('ja_JP');
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] =
                [
                    'id' => $i + 1,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => 1,
                    'can_use' => $faker->randomElement([0, 1]),
                    'coupon_type' => [
                        'id' => $faker->randomElement([0, 1]),
                        'name' => $faker->name,
                        'icon_url' => $faker->imageUrl(46, 46, 'food')
                    ],
                ];
        }
        return $this->view(BaseResponse::getData($data, [
            'limit' => 20,
            'offset' => 0,
            'item_total' => 20,
            'page_total' => 1,
            'current_page' => 1
        ]));

    }

    /**
     * Get List Used Coupon
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list coupon",
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
     *   }
     * )
     * @Get("/used/coupons")
     * @return Response
     */
    public function getUsedCouponAction(Request $request)
    {
        $pageSize = $request->request->get('page_size');
        //$pageSize = $request->request->get('page_size'); not work
        $pageSize = isset($_GET["page_size"]) ? $_GET["page_size"] : 10;
        //$pageIndex = $request->request->get('page_index'); not work
        $pageIndex = isset($_GET["page_index"]) ? $_GET["page_index"] : 1;
        /**@var AppUser $appUser*/
        $appUser = $this->getUser();

        if(!$appUser) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found'
            ) , 404);
        }

        if ($appUser){
            $userLoginId = $appUser->getId();
            if(!$userLoginId) {
                return $this->view($this->get('pon.exception.exception_handler')->throwError(
                    'app_user.not_found'
                ) , 404);
            }
        }

        $managerUseList = $this->getUseListManager();
        /**@var Coupon $coupon*/
        $listCouponTmp = $managerUseList->listCoupon(array("page_size" => $pageSize, "page_index" => $pageIndex, "app_user_id" => $userLoginId));
        $listCouponTmp = $this->getSerializer()->serialize($listCouponTmp, ['view_coupon_list']);

        $listCoupon = [];
        if(!empty($listCouponTmp["data"])) {
            $listCoupon["pagination"] = $listCouponTmp["pagination"];
            foreach ($listCouponTmp['data'] as $k => $v) {
                $coupon = $v["coupon"];
                $listCoupon['data'][$k]['id'] = $coupon["id"];
                $listCoupon['data'][$k]['couponType'] = $coupon["couponType"];
                $listCoupon['data'][$k]['title'] = $coupon["title"];
                $listCoupon['data'][$k]['image_url'] = $coupon["image_url"];
                $listCoupon['data'][$k]['expired_time'] = $coupon["expired_time"];
                $listCoupon['data'][$k]['is_like'] = 1;
                $listCoupon['data'][$k]['can_use'] = 0;
                if ($coupon["status"] == 1) {
                    $listCoupon['data'][$k]['can_use'] = 1;
                }
            }
        }
        return $this->view($listCoupon);


        $faker = Factory::create('ja_JP');
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] =
                [
                    'id' => $i + 1,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => $faker->randomElement([0, 1]),
                    'can_use' => $faker->randomElement([0, 1]),
                    'coupon_type' => [
                        'id' => $faker->randomElement([0, 1]),
                        'name' => $faker->name,
                        'icon_url' => $faker->imageUrl(46, 46, 'food')
                    ],
                ];
        }
        return $this->view(BaseResponse::getData($data, [
            'limit' => 20,
            'offset' => 0,
            'item_total' => 20,
            'page_total' => 1,
            'current_page' => 1
        ]));

    }

    /**
     * Like Coupon
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to like coupon",
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
     *     401 = "Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The App User is not found"
     *   }
     * )
     *
     * @Post("/like/coupons/{id}", name="like_coupon")
     * @return Response
     */
    public function postLikeCouponAction($id, Request $request)
    {
        $appUser = $this->getUser();
        if(!$appUser) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found'
            ) , 401);
        }

        $couponId = (int)$id;
        if ($couponId > 0) {
            $manager = $this->getManager();
            /**@var Coupon $coupon*/
            $coupon = $manager->findOneById($couponId);
            if (!$coupon) {
                return $this->view($this->get('pon.exception.exception_handler')->throwError(
                    'coupon.not_found'
                ) , 404);

            }
        }

        $managerLikeList = $this->getLikeListManager();
        /**@var LikeList $likeList*/
        $likeList = new LikeList();
        $likeList->setCoupon($coupon);
        $likeList->setAppUser($appUser);
        $likeList = $managerLikeList->createLikeList($likeList);
        if (!$likeList) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'like_list.fail'
            ) , 404);
        }
        return $this->view(BaseResponse::getData([]), 200);
    }

    /**
     * Use Coupon
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to like coupon",
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
     *   }
     * )
     *
     * @Post("/use/coupons/{id}", name="use_coupon")
     * @return Response
     */
    public function postUseCouponAction($id, Request $request)
    {
        $appUser = $this->getUser();
        if(!$appUser) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'app_user.not_found'
            ) , 401);
        }

        $couponId = (int)$id;
        if ($couponId > 0) {
            $manager = $this->getManager();
            /**@var Coupon $coupon*/
            $coupon = $manager->findOneById($couponId);
            if (!$coupon) {
                return $this->view($this->get('pon.exception.exception_handler')->throwError(
                    'coupon.not_found'
                ) , 404);

            }
        }

        $managerUseList = $this->getUseListManager();
        /**@var UseList $useList*/
        $useList = new UseList();
        $useList->setCoupon($coupon);
        $useList->setAppUser($appUser);
        $useList = $managerUseList->createUseList($useList);
        if (!$useList) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'use_list.fail'
            ) , 404);
        }
        return $this->view(BaseResponse::getData([]), 200);
    }

    /**
     * @return CouponManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.coupon');
    }

    /**
     *
     * @return StoreManager
     */
    public function getStoreManager()
    {
        return $this->get('pon.manager.store');
    }

    /**
     * @return CouponTypeManager
     */
    public function getCouponTypeManager()
    {
        return $this->get('pon.manager.coupon_type');
    }

    /**
     * @return LikeListManager
     */
    public function getLikeListManager()
    {
        return $this->get('pon.manager.like_list');
    }

    /**
     * @return UseListManager
     */
    public function getUseListManager()
    {
        return $this->get('pon.manager.use_list');
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }
}
