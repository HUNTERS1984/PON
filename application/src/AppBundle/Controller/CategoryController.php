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
use FOS\RestBundle\Controller\Annotations\View;
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
     *  section="Category",
     *  resource=false,
     *  description="This api is used to list Category (DONE)",
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
     *   },
     *   views = { "app"}
     * )
     * @Get("/categories")
     * @View(serializerGroups={"list_category"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getCategoriesAction(Request $request)
    {
        $params = $request->query->all();
        $result = $this->getManager()->getCategories($params);

        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));
    }

    /**
     * Get List Category Include shop total
     * @ApiDoc(
     *  section="Category",
     *  resource=false,
     *  description="This api is used to list category (DONE)",
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
     *   },
     *   views = { "app"}
     * )
     * @Get("/categories/shop")
     * @View(serializerGroups={"list_category_count"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getCategoriesIncludeShopAction(Request $request)
    {
        $params = $request->query->all();
        $result = $this->getManager()->getCategories($params);
        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));
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
