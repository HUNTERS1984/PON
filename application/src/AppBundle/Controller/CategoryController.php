<?php

namespace AppBundle\Controller;

use CoreBundle\Manager\CategoryManager;
use CoreBundle\Manager\StoreManager;
use CoreBundle\Serializator\Serializer;
use Faker\Factory;
use FOS\RestBundle\Controller\Annotations\Delete;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CoreBundle\Utils\Response as BaseResponse;


class CategoryController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get List Category
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list Category",
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
     *   }
     * )
     * @Get("/categories")
     * @return Response
     */
    public function getCategoriesAction(Request $request)
    {
        $params = $request->query->all();
        $result = $this->getManager()->getCategories($params);

        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));
        $faker = Factory::create('ja_JP');

        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'id' => $i+1,
                'name' => $faker->name,
                'icon_url' => $faker->imageUrl(640, 480, 'food')
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
     * Get List Category Include shop total
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list category",
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
     *   }
     * )
     * @Get("/categories/shop")
     * @return Response
     */
    public function getCategoriesIncludeShopAction(Request $request)
    {
        $faker = Factory::create('ja_JP');

        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'id' => $i+1,
                'name' => $faker->name,
                'shop_count' => $faker->numberBetween(1, 100),
                'icon_url' => $faker->imageUrl(640, 480, 'food')
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
     * @return CategoryManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.category');
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }

    /**
     * @return StoreManager
     */
    public function getStoreManager()
    {
        return $this->get('pon.manager.store');
    }
}
