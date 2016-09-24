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
     * Create Store
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to create store",
     *  requirements={
     *      {
     *          "name"="store_type_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of store type"
     *      },
     *      {
     *          "name"="user_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of user"
     *      }
     *  },
     *  input={
     *       "class"="CoreBundle\Entity\Store",
     *       "groups"={"create_store"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\Store",
     *       "groups"={"view"}
     *     },
     *  statusCodes = {
     *     201 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input"
     *   }
     * )
     * @return Response
     */
    public function postAction(Request $request)
    {

        $storeTypeId = $request->request->get('store_type_id');
        $userId =  $request->request->get('user_id');
        $storeType = null;
        $user = null;
        if($storeTypeId){
            /**@var StoreTypeManager $storeTypeManager */
            $storeTypeManager = $this->getStoreTypeManager();
            $storeType = $storeTypeManager->findOneById($storeTypeId);

            if(!$storeType) {
                $this->get('pon.exception.exception_handler')->throwError(
                    'store_type.not_found'
                );
            }
        }

        if($userId) {
            $userManager = $this->getUserManager();
            /**@var User $user*/
            $user = $userManager->findOneById($userId);

            if(!$user || !$user->isEnabled()) {
                $this->get('pon.exception.exception_handler')->throwError(
                    'user.not_found'
                );
            }
        }

        $form = $this->createForm(StoreType::class, new Store());
        $form->submit($request->request->all());

        /**@var Store $store*/
        $store = $form->getData();
        $store->setStoreType($storeType);
        $store->setUser($user);

        if($error = $this->get('pon.exception.exception_handler')->validate($store)) {
            return $error;
        }


        $this->getManager()->createStore($store);
        return $this->view($store, 201);
    }

    /**
     * Update Store
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to update store",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of store"
     *      },
     *     {
     *          "name"="store_type_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of store type"
     *      },
     *      {
     *          "name"="user_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of user"
     *      }
     *  },
     *  input={
     *       "class"="CoreBundle\Entity\Store",
     *       "groups"={"create_store"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\Store",
     *       "groups"={"view"}
     *     },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Store Type is not found"
     *   }
     * )
     * @return Response
     */
    public function putAction($id ,Request $request)
    {
        $manager = $this->getManager();
        /**@var Store $store*/
        $store = $manager->findOneById($id);
        if(!$store) {
            $this->get('pon.exception.exception_handler')->throwError(
                'store.not_found'
            );
        }

        $storeTypeId = $request->request->get('store_type_id');
        $userId =  $request->request->get('user_id');
        if($storeTypeId) {
            /**@var StoreTypeManager $storeTypeManager */
            $storeTypeManager = $this->getStoreTypeManager();
            $storeType = $storeTypeManager->findOneById($storeTypeId);

            if(!$storeType) {
                $this->get('pon.exception.exception_handler')->throwError(
                    'store_type.not_found'
                );
            }
            $store->setStoreType($storeType);
        }

        if($userId) {
            $userManager = $this->getUserManager();
            /**@var User $user*/
            $user = $userManager->findOneById($userId);

            if(!$user || !$user->isEnabled()) {
                $this->get('pon.exception.exception_handler')->throwError(
                    'user.not_found'
                );
            }
            $store->setUser($user);
        }

        $store = $this->get('pon.utils.data')->setData($request->request->all(), $store);

        if($error = $this->get('pon.exception.exception_handler')->validate($store)) {
            return $error;
        }

        $this->getManager()->saveStore($store);
        return $this->view($store, 200);
    }

    /**
     * Delete Store
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to delete store",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of store"
     *      }
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the store is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Store is not found"
     *   }
     * )
     * @return Response
     */
    public function deleteAction($id)
    {
        /**@var StoreManager $manager*/
        $manager = $this->getManager();
        /**@var Store $store*/
        $store = $manager->findOneById($id);
        if(!$store) {
            $this->get('pon.exception.exception_handler')->throwError(
                'store.not_found'
            );
        }

        $status = $manager->deleteStore($store);
        if(!$status) {
            $this->get('pon.exception.exception_handler')->throwError(
                'store.delete_false'
            );
        }

        return $this->view(null, 200);
    }

    /**
     * View Detail Store
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to view store",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of store"
     *      }
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\Store",
     *     "groups"={"view"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the store is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Store is not found"
     *   }
     * )
     * @Get("/shop/{id}")
     * @return Response
     */
    public function getAction($id)
    {
        $faker = Factory::create();
        $data = [
            'id' => $id,
            'title' => $faker->name,
            'shop_type' => 1,
            'operation_start_time' => time(),
            'operation_end_time' => time(),
            'image_url' => $faker->imageUrl(),
            'is_follow' => $faker->randomElement(0,1),
            'tel' => $faker->phoneNumber,
            'lattitude' => $faker->latitude,
            'longitude' => $faker->longitude,
            'address' => $faker->address,
            'close_date' => "Saturday and Sunday",
            'ave_bill' => $faker->numberBetween(100,200)
        ];
        return $this->view(BaseResponse::getData($data));

        $manager = $this->getManager();
        /**@var Store $store*/
        $store = $manager->findOneById($id);
        if(!$store || !is_null($store->getDeletedAt())) {
            $this->get('pon.exception.exception_handler')->throwError(
                'store.not_found'
            );
        }

        $data = $this->getSerializer()->serialize($store, ['view']);

        return $this->view($data, 200);
    }

    /**
     * Get List Store
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list store",
     *  parameters={
     *      {"name"="page_size", "dataType"="integer", "required"=false, "description"="page size to return"},
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"},
     *      {"name"="name", "dataType"="string", "required"=false, "description"="name of store"}
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\Store",
     *     "groups"={"view"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Store is not found"
     *   }
     * )
     * @Get("/shops")
     * @return Response
     */
    public function cgetAction(Request $request)
    {
        $faker = Factory::create('ja_JP');
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'id' => $faker->numberBetween(1,200),
                'title' => $faker->name,
                'shop_type' => [
                    'id' => $faker->numberBetween(1,200),
                    'name' => $faker->name,
                    'icon_url' => $faker->imageUrl()
                ],
                'shop_photo_url' => [
                  $faker->imageUrl(),
                  $faker->imageUrl(),
                  $faker->imageUrl(),
                  $faker->imageUrl(),
                ],
                'operation_start_time' => time(),
                'operation_end_time' => time(),
                'avatar_url' => $faker->imageUrl(),
                'is_follow' => $faker->randomElement(0,1),
                'tel' => $faker->phoneNumber,
                'lattitude' => $faker->latitude,
                'longitude' => $faker->longitude,
                'address' => $faker->address,
                'close_date' => "Saturday and Sunday",
                'ave_bill' => $faker->numberBetween(100,200)
            ];
        }
        return $this->view(BaseResponse::getData($data), 200, [
            'X-Pon-Limit' => 20,
            'X-Pon-Offset' => 0,
            'X-Pon-Item-Total' => 20,
            'X-Pon-Page-Total' => 1,
            'X-Pon-Current-Page' => 1
        ]);

        $params = $request->query->all();
        $data = $this->getManager()->listStore($params);
        $stores = $this->getSerializer()->serialize($data['data'], ['view']);
        return $this->view($stores, 200, $data['pagination']);
    }

    /**
     * Get Shop By Map
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to get list shop by map",
     *  parameters={
     *      {"name"="page_size", "dataType"="integer", "required"=false, "description"="page size to return"},
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"}
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\Store",
     *     "groups"={"view"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Shop is not found"
     *   }
     * )
     * @Get("/shop/maps/{longtitude}/{latitude}")
     * @return Response
     */
    public function getShopByMapAction($longtitude, $latitude, Request $request)
    {
        $faker = Factory::create('ja_JP');
        $data = [];
        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'id' => $faker->numberBetween(1,200),
                'title' => $faker->name,
                'shop_type' => [
                    'id' => $faker->numberBetween(1,200),
                    'name' => $faker->name,
                    'icon_url' => $faker->imageUrl()
                ],
                'operation_start_time' => time(),
                'operation_end_time' => time(),
                'image_url' => $faker->imageUrl(),
                'is_follow' => $faker->randomElement(0,1),
                'tel' => $faker->phoneNumber,
                'lattitude' => $latitude,
                'longitude' => $longtitude,
                'address' => $faker->address,
                'close_date' => "Saturday and Sunday",
                'ave_bill' => $faker->numberBetween(100,200)
            ];
        }
        return $this->view(BaseResponse::getData($data), 200, [
            'X-Pon-Limit' => 20,
            'X-Pon-Offset' => 0,
            'X-Pon-Item-Total' => 20,
            'X-Pon-Page-Total' => 1,
            'X-Pon-Current-Page' => 1
        ]);

        $params = $request->query->all();
        $data = $this->getManager()->listCoupon($params);
        $coupons = $this->getSerializer()->serialize($data['data'], ['view', 'view_coupon']);
        return $this->view($coupons, 200, $data['pagination']);
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
