<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\Coupon;
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
        if($type == 3 && (empty($params['latitude']) || empty($params['longitude']))) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_blank.latitude_longitude'
            ));
        }

        $user = $this->getUser();

        $result = $this->getManager()->getFeaturedCoupon($type, $params, $user);
        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));

        $faker = Factory::create('ja_JP');
        $user = $this->getUser();
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
                        'is_like' => $faker->randomElement([true, false]),
                        'need_login' => $needLogin = $faker->randomElement([true, false]),
                        'can_use' => (!$needLogin) || ($needLogin && $user) ? true : false,
                        'coupon_type' => [
                            'id' => $faker->randomElement([1, 2]),
                            'name' => $faker->name
                        ]
                    ],
                    [
                        'id' => $j + 2,
                        'title' => $faker->name,
                        'image_url' => $faker->imageUrl(640, 480, 'food'),
                        'expired_time' => new \DateTime(),
                        'is_like' => $faker->randomElement([true, false]),
                        'need_login' => $needLogin = $faker->randomElement([true, false]),
                        'can_use' => (!$needLogin) || ($needLogin && $user) ? true : false,
                        'coupon_type' => [
                            'id' => $faker->randomElement([1, 2]),
                            'name' => $faker->name
                        ]
                    ],
                    [
                        'id' => $j + 3,
                        'title' => $faker->name,
                        'image_url' => $faker->imageUrl(640, 480, 'food'),
                        'expired_time' => new \DateTime(),
                        'is_like' => $faker->randomElement([true, false]),
                        'need_login' => $needLogin = $faker->randomElement([true, false]),
                        'can_use' => (!$needLogin) || ($needLogin && $user) ? true : false,
                        'coupon_type' => [
                            'id' => $faker->randomElement([1, 2]),
                            'name' => $faker->name
                        ]
                    ],
                    [
                        'id' => $j + 4,
                        'title' => $faker->name,
                        'image_url' => $faker->imageUrl(640, 480, 'food'),
                        'expired_time' => new \DateTime(),
                        'is_like' => $faker->randomElement([true, false]),
                        'need_login' => $needLogin = $faker->randomElement([true, false]),
                        'can_use' => (!$needLogin) || ($needLogin && $user) ? true : false,
                        'coupon_type' => [
                            'id' => $faker->randomElement([1, 2]),
                            'name' => $faker->name
                        ]
                    ],
                    [
                        'id' => $j + 5,
                        'title' => $faker->name,
                        'image_url' => $faker->imageUrl(640, 480, 'food'),
                        'expired_time' => new \DateTime(),
                        'is_like' => $faker->randomElement([true, false]),
                        'need_login' => $needLogin = $faker->randomElement([true, false]),
                        'can_use' => (!$needLogin) || ($needLogin && $user) ? true : false,
                        'coupon_type' => [
                            'id' => $faker->randomElement([1, 2]),
                            'name' => $faker->name
                        ]
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

        if(!$categoryObject = $this->getCategoryManager()->getCategory($category)) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_found'
            ));
        }

        if($type == 3 && (empty($params['latitude']) || empty($params['longitude']))) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_blank.latitude_longitude'
            ));
        }

        $user = $this->getUser();
        $result = $this->getManager()->getFullFeaturedCoupon($type, $categoryObject, $params, $user);

        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));

        $faker = Factory::create('ja_JP');
        $user = $this->getUser();
        $data = [
            'id' => $category,
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
                    'is_like' => $faker->randomElement([true, false]),
                    'need_login' => $needLogin = $faker->randomElement([true, false]),
                    'can_use' => (!$needLogin) || ($needLogin && $user) ? true : false,
                    'coupon_type' => [
                        'id' => $faker->randomElement([1, 2]),
                        'name' => $faker->name
                    ]
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

        $coupon->setImpression($coupon->getImpression()+1);

        $coupon = $this->getManager()->saveCoupon($coupon);

        return $this->view(BaseResponse::getData($coupon));

        $user = $this->getUser();
        $faker = Factory::create('ja_JP');
        $couponPhotoUrl = [];
        $userPhotoUrl = [];
        for ($i = 1; $i < $id; $i++) {
            $couponPhotoUrl[] = $faker->imageUrl(640, 480, 'food');
            $userPhotoUrl[] = $faker->imageUrl(640, 480, 'food');
        }

        $needLogin = $faker->randomElement([true, false]);
        $canUse = (!$needLogin) || ($needLogin && $user) ? true : false;

        if (!$canUse) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.need_to_login'
            ));
        }

        $description = '説明が入ります説明が入ります説明が入ります説明が入り
ます説明が入ります説明が入ります説明が入ります説明が
入ります説明が入ります説明が入ります説明が入ります説
明が入ります説明が入ります説明が入ります説明が入りま
す説明が入ります..説明が入ります説明が入ります説明が
入ります説明が入ります説明が入ります説明が入ります説
明が入ります説明が入ります..説明が入ります説明が入り
ます説明が入ります説明が入ります説明が入ります説明が
入ります説明が入ります説明が入ります';

        $data = [
            'id' => (int)$id,
            'title' => $faker->name,
            'expired_time' => new \DateTime(),
            'image_url' => $faker->imageUrl(640, 480, 'food'),
            'is_like' => $faker->randomElement([true, false]),
            'need_login' => $needLogin,
            'can_use' => $canUse,
            'code' => $faker->ean13,
            'description' => $description,
            'shop' => [
                'id' => $faker->numberBetween(1, 200),
                'title' => $faker->company,
                'operation_start_time' => '08:00',
                'operation_end_time' => '23:00',
                'avatar_url' => $faker->imageUrl(640, 480, 'food'),
                'is_follow' => $faker->randomElement([true, false]),
                'tel' => $faker->phoneNumber,
                'latitude' => '35.911594',
                'longitude' => '137.746582',
                'address' => $faker->address,
                'close_date' => "Saturday and Sunday",
                'ave_bill' => $faker->numberBetween(100, 200),
                'category' => [
                    'id' => $faker->randomElement([1, 2]),
                    'name' => $faker->name,
                    'icon_url' => $faker->imageUrl(46, 46, 'food')
                ]
            ],
            'coupon_type' => [
                'id' => $faker->randomElement([1, 2]),
                'name' => $faker->name,
            ],
            'coupon_photo_url' => $couponPhotoUrl,
            'user_photo_url' => $userPhotoUrl,
            'similar_coupon' => [
                [
                    'id' => 1,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => $faker->randomElement([true, false]),
                    'can_use' => $faker->randomElement([true, false]),
                    'coupon_type' => [
                        'id' => $faker->randomElement([1, 2]),
                        'name' => $faker->name,
                    ],
                ],
                [
                    'id' => 2,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => $faker->randomElement([true, false]),
                    'can_use' => $faker->randomElement([true, false]),
                    'coupon_type' => [
                        'id' => $faker->randomElement([1, 2]),
                        'name' => $faker->name,
                    ],
                ],
                [
                    'id' => 3,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => $faker->randomElement([true, false]),
                    'can_use' => $faker->randomElement([true, false]),
                    'coupon_type' => [
                        'id' => $faker->randomElement([1, 2]),
                        'name' => $faker->name,
                    ],
                ],
                [
                    'id' => 4,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => $faker->randomElement([true, false]),
                    'can_use' => $faker->randomElement([true, false]),
                    'coupon_type' => [
                        'id' => $faker->randomElement([1, 2]),
                        'name' => $faker->name,
                    ],
                ],
            ],
        ];

        return $this->view(BaseResponse::getData($data));
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


        $faker = Factory::create('ja_JP');
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] =
                [
                    'id' => $i + 1,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => true,
                    'need_login' => $needLogin = $faker->randomElement([true, false]),
                    'can_use' => (!$needLogin) || ($needLogin && $user) ? true : false,
                    'shop' => [
                        'id' => $faker->numberBetween(1, 200),
                        'title' => $faker->company,
                        'category' => [
                            'id' => $faker->randomElement([1, 2]),
                            'name' => $faker->name,
                            'icon_url' => $faker->imageUrl(46, 46, 'food')
                        ]
                    ],
                    'coupon_type' => [
                        'id' => $faker->randomElement([1, 2]),
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

        $user = $this->getUser();
        $faker = Factory::create('ja_JP');
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] =
                [
                    'id' => $i + 1,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => $faker->randomElement([true, false]),
                    'need_login' => $needLogin = $faker->randomElement([true, false]),
                    'can_use' => (!$needLogin) || ($needLogin && $user) ? true : false,
                    'shop' => [
                        'id' => $faker->numberBetween(1, 200),
                        'title' => $faker->company,
                        'category' => [
                            'id' => $faker->randomElement([1, 2]),
                            'name' => $faker->name,
                            'icon_url' => $faker->imageUrl(46, 46, 'food')
                        ]
                    ],
                    'coupon_type' => [
                        'id' => $faker->randomElement([1, 2]),
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

        $isLike = $this->getLikeListManager()->isLike($user, $coupon);
        if(!$isLike) {
            $coupon = $this->getManager()->likeCoupon($user, $coupon);
            if(!$coupon) {
                return $this->view($this->get('pon.exception.exception_handler')->throwError(
                    'coupon.like.not_success'
                ));
            }
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
        if(!$useCoupon) {
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

        $faker = Factory::create('ja_JP');
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] =
                [
                    'coupon' => [
                        'title' => $faker->name
                    ],
                    'code' => $faker->ean13,
                    'user' => [
                        'username' => $faker->userName,
                        'name' => $faker->name
                    ]
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

        $faker = Factory::create('ja_JP');
        $data = [
            'title' => $faker->name,
            'code' => $code,
            'user' => [
                'username' => $faker->userName,
                'name' => $faker->name
            ]
        ];
        return $this->view(BaseResponse::getData($data, [
            'limit' => 20,
            'offset' => 0,
            'item_total' => 20,
            'page_total' => 1,
            'current_page' => 1
        ]));
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

        $appUser = $this->getUser();
        $coupon = $this->getManager()->getCoupon($id);
        if (!$coupon) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_found'
            ));
        }

        $isLike = $this->getLikeListManager()->isLike($user, $coupon);
        if(!$isLike) {
            $coupon = $this->getManager()->likeCoupon($user, $coupon);
            if(!$coupon) {
                return $this->view($this->get('pon.exception.exception_handler')->throwError(
                    'coupon.like.not_success'
                ));
            }
        }

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

        $appUser = $this->getUser();
        $coupon = $this->getManager()->getCoupon($id);
        if (!$coupon) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_found'
            ));
        }

        $isLike = $this->getLikeListManager()->isLike($user, $coupon);
        if(!$isLike) {
            $coupon = $this->getManager()->likeCoupon($user, $coupon);
            if(!$coupon) {
                return $this->view($this->get('pon.exception.exception_handler')->throwError(
                    'coupon.like.not_success'
                ));
            }
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
