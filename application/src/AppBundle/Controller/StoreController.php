<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\Store;
use CoreBundle\Entity\User;
use CoreBundle\Form\Type\StoreType;
use CoreBundle\Manager\StoreManager;
use CoreBundle\Manager\StoreTypeManager;
use CoreBundle\Manager\UserManager;
use Faker\Factory;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CoreBundle\Utils\Response as BaseResponse;


class StoreController extends FOSRestController  implements ClassResourceInterface
{
    /**
     * View Shop Detail
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to view shop detail",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of shop"
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
     * @Get("/shops/{id}")
     * @return Response
     */
    public function getAction($id)
    {
        $faker = Factory::create('ja_JP');
        $data = [
            'id' => (int)$id,
            'title' => $faker->company,
            'operation_start_time' =>  '08:00',
            'operation_end_time' => '23:00',
            'avatar_url' => $faker->imageUrl(640, 480, 'food'),
            'is_follow' => $faker->randomElement([0, 1]),
            'tel' => $faker->phoneNumber,
            'lattitude' => '35.911594',
            'longitude' => '137.746582',
            'address' => $faker->address,
            'close_date' => "Saturday and Sunday",
            'ave_bill' => $faker->numberBetween(100, 200),
            'help_text' => $faker->paragraph(2),
            'shop_photo_url' => [
                $faker->imageUrl(640, 480, 'food'),
                $faker->imageUrl(640, 480, 'food'),
                $faker->imageUrl(640, 480, 'food'),
                $faker->imageUrl(640, 480, 'food')
            ],
            'coupons' => [
                [
                    'id' => 1,
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
                ],
                [
                    'id' => 2,
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
                ],
                [
                    'id' => 3,
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
                ],
                [
                    'id' => 4,
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
                ],
            ]
        ];

        return $this->view(BaseResponse::getData($data));
    }

    /**
     * Get List Feature Shop Follow Featured And Coupon Type
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list shop",
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
     * @Get("/featured/{type}/shops/{couponType}")
     * @return Response
     */
    public function getByFeaturedAndTypeAction($type, $couponType, Request $request)
    {
        $faker = Factory::create('ja_JP');
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] =
                [
                    'id' => $i+1,
                    'title' => $faker->company,
                    'avatar_url' => $faker->imageUrl(640, 480, 'food'),
                    'is_follow' => $faker->randomElement([0, 1]),
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
     * Get List Shop follow coupon type
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list shop follow coupon type",
     *  requirements={
     *      {
     *          "name"="type",
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
     * @Get("/coupon/{type}/shops")
     * @return Response
     */
    public function getShopByCouponTypeAction($type, Request $request)
    {
        $faker = Factory::create('ja_JP');
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] =
                [
                    'id' => $i+1,
                    'title' => $faker->company,
                    'avatar_url' => $faker->imageUrl(640, 480, 'food'),
                    'is_follow' => $faker->randomElement([0, 1]),
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
     * Get List Follow Shop
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
     * @Get("/follow/shops")
     * @return Response
     */
    public function getFollowShopAction(Request $request)
    {
        $faker = Factory::create('ja_JP');
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] =
                [
                    'id' => $i+1,
                    'title' => $faker->company,
                    'avatar_url' => $faker->imageUrl(640, 480, 'food'),
                    'is_follow' => 1,
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
     * Follow Shop
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to follow shop",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="string",
     *          "description"="id of shop"
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
     * @Post("/follow/shops/{id}", name="follow_shop")
     * @return Response
     */
    public function postFollowShopAction($id, Request $request)
    {
        return $this->view(BaseResponse::getData([]), 200);
    }

    /**
     * Get Shop Coupons By Map
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list coupon",
     *  requirements={
     *      {
     *          "name"="lattitude",
     *          "dataType"="string",
     *          "description"="lattitude of app"
     *      },
     *     {
     *          "name"="longitude",
     *          "dataType"="string",
     *          "description"="longitude of app"
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
     * @Get("/map/{lattitude}/{longitude}/shops")
     * @return Response
     */
    public function getShopCouponByMapAction($lattitude, $longitude, Request $request)
    {
        $faker = Factory::create('ja_JP');
        $data = [];
        $j = 0;
        $arrayGeo = [
            [10.785871, 106.6851],
            [10.784338, 106.684574],
            [10.788322, 106.685196],
            [10.786783, 106.683758],
            [10.785076, 106.682965],
            [10.785919, 106.686226],
            [10.839665, 106.779503],
            [10.839812, 106.780339],
            [10.840795, 106.778982],
            [10.840592, 106.777550],
        ];
        for ($i = 0; $i < 10; $i++) {
            $data[] = [
                'id' => $i+1,
                'title' => $faker->company,
                'operation_start_time' =>  '08:00',
                'operation_end_time' => '23:00',
                'avatar_url' => $faker->imageUrl(640, 480, 'food'),
                'is_follow' => $faker->randomElement([0, 1]),
                'tel' => $faker->phoneNumber,
                'lattitude' => (string)$arrayGeo[$i][0],
                'longitude' => (string)$arrayGeo[$i][1],
                'address' => $faker->address,
                'close_date' => "Saturday and Sunday",
                'ave_bill' => $faker->numberBetween(100, 200),
                'help_text' => $faker->paragraph(2),
                'shop_photo_url' => [
                    $faker->imageUrl(640, 480, 'food'),
                    $faker->imageUrl(640, 480, 'food'),
                    $faker->imageUrl(640, 480, 'food'),
                    $faker->imageUrl(640, 480, 'food')
                ],
                'coupons' => [
                    [
                        'id' => 1,
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
                    ],
                    [
                        'id' => 2,
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
                    ],
                    [
                        'id' => 3,
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
                    ],
                    [
                        'id' => 4,
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
                    ],
                ]
            ];
            $j = $j + 5;
        }
        return $this->view(BaseResponse::getData($data, [
            'limit' => 20,
            'offset' => 0,
            'item_total' => 10,
            'page_total' => 1,
            'current_page' => 1
        ]));

    }

    /**
     * @return StoreManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.store');
    }

    /**
     * @return StoreTypeManager
     */
    public function getStoreTypeManager()
    {
        return $this->get('pon.manager.store_type');
    }

    /**
     * @return UserManager
     */
    public function getUserManager()
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
