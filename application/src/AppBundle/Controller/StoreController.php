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
            'id' => $id,
            'title' => $faker->company,
            'operation_start_time' => new \DateTime(),
            'operation_end_time' => new \DateTime(),
            'avatar_url' => $faker->imageUrl(640, 480, 'food'),
            'is_follow' => $faker->randomElement([0, 1]),
            'tel' => $faker->phoneNumber,
            'lattitude' => $faker->latitude,
            'longitude' => $faker->longitude,
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
                    'can_use' => $faker->randomElement([0, 1])
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
            ]
        ];

        return $this->view($data, 200);
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
