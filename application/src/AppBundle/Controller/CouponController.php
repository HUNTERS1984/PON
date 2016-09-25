<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\Coupon;
use CoreBundle\Form\Type\CouponType;
use CoreBundle\Form\Type\CouponTypeType;
use CoreBundle\Manager\CouponManager;
use CoreBundle\Manager\CouponTypeManager;
use Faker\Factory;
use FOS\RestBundle\Controller\Annotations\Get;
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
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Coupon is not found"
     *   }
     * )
     * @Get("/featured/{type}/coupons")
     * @return Response
     */
    public function getFeaturedCouponAction($type, Request $request)
    {
        $faker = Factory::create('ja_JP');
        $data = [];
        $j=0;
        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'id' => $i + 1,
                'name' => $faker->name,
                'icon_url' => $faker->imageUrl(),
                'coupons' => [
                    [
                        'id' => $j+1,
                        'title' => $faker->name,
                        'imageUrl' => $faker->imageUrl(),
                        'expired_time' => time(),
                        'is_like' => $faker->randomElement([0, 1]),
                        'can_use' => $faker->randomElement([0, 1])
                    ],
                    [
                        'id' => $j+2,
                        'title' => $faker->name,
                        'imageUrl' => $faker->imageUrl(),
                        'expired_time' => time(),
                        'is_like' => $faker->randomElement([0, 1]),
                        'can_use' => $faker->randomElement([0, 1]),
                    ],
                    [
                        'id' => $j+3,
                        'title' => $faker->name,
                        'imageUrl' => $faker->imageUrl(),
                        'expired_time' => time(),
                        'is_like' => $faker->randomElement([0, 1]),
                        'can_use' => $faker->randomElement([0, 1]),
                    ],
                    [
                        'id' => $j+4,
                        'title' => $faker->name,
                        'imageUrl' => $faker->imageUrl(),
                        'expired_time' => time(),
                        'is_like' => $faker->randomElement([0, 1]),
                        'can_use' => $faker->randomElement([0, 1]),
                    ],
                    [
                        'id' => $j+5,
                        'title' => $faker->name,
                        'imageUrl' => $faker->imageUrl(),
                        'expired_time' => time(),
                        'is_like' => $faker->randomElement([0, 1]),
                        'can_use' => $faker->randomElement([0, 1]),
                    ],
                ]

            ];
            $j=$j+5;
        }
        return $this->view(BaseResponse::getData($data), 200, [
            'X-Pon-Limit' => 20,
            'X-Pon-Offset' => 0,
            'X-Pon-Item-Total' => 20,
            'X-Pon-Page-Total' => 1,
            'X-Pon-Current-Page' => 1
        ]);

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
     *     401="Returned when the coupon is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Coupon is not found"
     *   }
     * )
     * @return Response
     */
    public function getAction($id)
    {

        $faker = Factory::create('ja_JP');
        $data = [
            'id' => $id,
            'title' => $faker->name,
            'expired_time' => time(),
            'image_url' => $faker->imageUrl(),
            'is_like' => $faker->randomElement([0, 1]),
            'can_use' => $faker->randomElement([0, 1]),
            'code' => $faker->ean13,
            'description' => $faker->paragraph(6),
            'coupon_type' => [
                'id' => $faker->randomElement([0, 1]),
                'name' => $faker->name,
                'icon_url' => $faker->imageUrl(),
                'shop' => [
                    'id' => $faker->numberBetween(1, 200),
                    'title' => $faker->name,
                    'operation_start_time' => time(),
                    'operation_end_time' => time(),
                    'avatar_url' => $faker->imageUrl(),
                    'is_follow' => $faker->randomElement(0, 1),
                    'tel' => $faker->phoneNumber,
                    'lattitude' => $faker->latitude,
                    'longitude' => $faker->longitude,
                    'address' => $faker->address,
                    'close_date' => "Saturday and Sunday",
                    'ave_bill' => $faker->numberBetween(100, 200)
                ]
            ],
            'coupon_photo_url' => [
                $faker->imageUrl(),
                $faker->imageUrl(),
                $faker->imageUrl(),
                $faker->imageUrl()
            ],
            'user_photo_url' => [
                $faker->imageUrl(),
                $faker->imageUrl(),
                $faker->imageUrl(),
                $faker->imageUrl()
            ],
            'similar_coupon' => [
                [
                    'id' => 1,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(),
                    'expired_time' => time(),
                    'is_like' => $faker->randomElement([0, 1]),
                    'can_use' => $faker->randomElement([0, 1])
                ],
                [
                    'id' => 2,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(),
                    'expired_time' => time(),
                    'is_like' => $faker->randomElement([0, 1]),
                    'can_use' => $faker->randomElement([0, 1])
                ],
                [
                    'id' => 3,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(),
                    'expired_time' => time(),
                    'is_like' => $faker->randomElement([0, 1]),
                    'can_use' => $faker->randomElement([0, 1])
                ],
                [
                    'id' => 4,
                    'title' => $faker->name,
                    'image_url' => $faker->imageUrl(),
                    'expired_time' => time(),
                    'is_like' => $faker->randomElement([0, 1]),
                    'can_use' => $faker->randomElement([0, 1])
                ],
            ],
        ];

        return $this->view($data, 200);
    }

    /**
     * @return CouponManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.coupon');
    }

    /**
     * @return CouponTypeManager
     */
    public function getCouponTypeManager()
    {
        return $this->get('pon.manager.coupon_type');
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }
}
