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
            'is_follow' => $faker->randomElement(0, 1),
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
                    'imageUrl' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => $faker->randomElement([0, 1]),
                    'can_use' => $faker->randomElement([0, 1])
                ],
                [
                    'id' => 2,
                    'title' => $faker->name,
                    'imageUrl' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => $faker->randomElement([0, 1]),
                    'can_use' => $faker->randomElement([0, 1])
                ],
                [
                    'id' => 3,
                    'title' => $faker->name,
                    'imageUrl' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => $faker->randomElement([0, 1]),
                    'can_use' => $faker->randomElement([0, 1])
                ],
                [
                    'id' => 4,
                    'title' => $faker->name,
                    'imageUrl' => $faker->imageUrl(640, 480, 'food'),
                    'expired_time' => new \DateTime(),
                    'is_like' => $faker->randomElement([0, 1]),
                    'can_use' => $faker->randomElement([0, 1])
                ],
            ]
        ];

        return $this->view($data, 200);
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
