<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\Category;
use CoreBundle\Entity\Store;
use CoreBundle\Entity\User;
use CoreBundle\Manager\CategoryManager;
use CoreBundle\Manager\FollowListManager;
use CoreBundle\Manager\StoreManager;
use CoreBundle\Manager\StoreTypeManager;
use CoreBundle\Manager\UserManager;
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


class StoreController extends FOSRestController  implements ClassResourceInterface
{
    /**
     * View Shop Detail
     * @ApiDoc(
     *  section="Shop",
     *  resource=false,
     *  description="This api is used to view shop detail (DONE)",
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
     *   },
     *   views = { "app"}
     * )
     * @Get("/shops/{id}")
     * @View(serializerGroups={"list","store_category","store_coupon"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getAction($id)
    {
        $store = $this->getManager()->getStore($id);
        if (!$store) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'store.not_found'
            ));
        }

        $store->setImpression($store->getImpression()+1);

        $store = $this->getManager()->saveStore($store);

        return $this->view(BaseResponse::getData($store));
    }

    /**
     * Get List Feature Shop Follow Featured And Category
     * @ApiDoc(
     *  section="Shop",
     *  resource=false,
     *  description="This api is used to list shop  (DONE)",
     *  requirements={
     *      {
     *          "name"="type",
     *          "dataType"="integer",
     *          "description"="featured type (1,2,3)"
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
     *      {"name"="latitude", "dataType"="string", "required"=false, "description"="latitude of user"},
     *      {"name"="longitude", "dataType"="string", "required"=false, "description"="longitude of user"},
     *      {"name"="page_size", "dataType"="integer", "required"=false, "description"="page size to return"},
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"},
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app"}
     * )
     * @Get("/featured/{type}/shops/{category}")
     * @View(serializerGroups={"store_featured"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getByFeaturedAndTypeAction($type, $category, Request $request)
    {
        $params = $request->query->all();
        if($type == 3 && (empty($params['latitude']) || empty($params['longitude']))) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_blank.latitude_longitude'
            ));
        }

        $category = $this->getCategoryManager()->getCategory($category);

        if(!$category) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'category.not_found'
            ));
        }

        $result = $this->getManager()->getFeaturedStore($type, $params, $category);
        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));
    }

    /**
     * Get List Feature Shop Follow Featured
     * @ApiDoc(
     *  section="Shop",
     *  resource=false,
     *  description="This api is used to list shop (DONE)",
     *  requirements={
     *      {
     *          "name"="type",
     *          "dataType"="integer",
     *          "description"="featured type (1,2,3)"
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
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"},
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app"}
     * )
     * @Get("/featured/{type}/shops")
     * @View(serializerGroups={"store_featured"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getByFeaturedAction($type, Request $request)
    {
        $params = $request->query->all();
        if($type == 3 && (empty($params['latitude']) || empty($params['longitude']))) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_blank.latitude_longitude'
            ));
        }
        
        $result = $this->getManager()->getFeaturedStore($type, $params);
        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));
    }

    /**
     * Get List Shop follow category
     * @ApiDoc(
     *  section="Shop",
     *  resource=false,
     *  description="This api is used to list shop follow category (DONE)",
     *  requirements={
     *      {
     *          "name"="id",
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
     *      {"name"="page_size", "dataType"="integer", "required"=false, "description"="page size to return"},
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"},
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app"}
     * )
     * @Get("/category/{id}/shops")
     * @View(serializerGroups={"store_featured"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getShopByCategoryAction($id, Request $request)
    {
        $params = $request->query->all();
        $category = $this->getCategoryManager()->getCategory($id);

        if(!$category) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'category.not_found'
            ));
        }

        $result = $this->getManager()->getNewestStore($params, $category);
        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));
    }

    /**
     * Get List Follow Shop
     * @ApiDoc(
     *  section="Shop",
     *  resource=false,
     *  description="This api is used to list follow shops (DONE)",
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
     * @Get("/follow/shops")
     * @View(serializerGroups={"store_featured"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getFollowShopAction(Request $request)
    {
        $user = $this->getUser();
        $params = $request->query->all();
        $result = $this->getFollowListManager()->getFollowShops($user, $params);
        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));
    }

    /**
     * Follow Shop
     * @ApiDoc(
     *  section="Shop",
     *  resource=false,
     *  description="This api is used to follow shop (DONE)",
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
     *   },
     *   views = { "app"}
     * )
     *
     * @Post("/follow/shops/{id}", name="follow_shop")
     * @return Response
     */
    public function postFollowShopAction($id, Request $request)
    {
        $user = $this->getUser();
        $store = $this->getManager()->getStore($id);
        if (!$store) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'store.not_found'
            ));
        }

        $followList = $this->getFollowListManager()->isFollow($user, $store);
        if(!$followList) {
            $store = $this->getFollowListManager()->followStore($user, $store);
            if(!$store) {
                return $this->view($this->get('pon.exception.exception_handler')->throwError(
                    'store.follow.not_success'
                ));
            }
        }

        return $this->view(BaseResponse::getData([]), 200);
    }

    /**
     * UnFollow Shop
     * @ApiDoc(
     *  section="Shop",
     *  resource=false,
     *  description="This api is used to unfollow shop (DONE)",
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
     *   },
     *   views = { "app"}
     * )
     *
     * @Post("/unfollow/shops/{id}", name="unfollow_shop")
     * @return Response
     */
    public function postUnFollowShopAction($id, Request $request)
    {
        $user = $this->getUser();
        $store = $this->getManager()->getStore($id);
        if (!$store) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'store.not_found'
            ));
        }

        $followList = $this->getFollowListManager()->isFollow($user, $store);

        if (!$followList) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.like.not_exists'
            ));
        }

        $result = $this->getFollowListManager()->unFollowStore($followList);
        if (!$result) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'coupon.unfollow.not_success'
            ));
        }

        return $this->view(BaseResponse::getData([]), 200);
    }

    /**
     * Get Shop Coupons By Map
     * @ApiDoc(
     *  section="Shop",
     *  resource=false,
     *  description="This api is used to list coupon (DONE)",
     *  requirements={
     *      {
     *          "name"="latitude",
     *          "dataType"="string",
     *          "description"="latitude of app"
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
     *   },
     *   views = { "app"}
     * )
     * @Get("/map/{latitude}/{longitude}/shops")
     * @View(serializerGroups={"list","store_category","store_coupon"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getShopCouponByMapAction($latitude, $longitude, Request $request)
    {
        $params = $request->query->all();
        $params['latitude'] = $latitude;
        $params['longitude'] = $longitude;

        $result = $this->getManager()->filterShopByMap($params);
        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));
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
     * @return CategoryManager
     */
    public function getCategoryManager()
    {
        return $this->get('pon.manager.category');
    }

    /**
     * @return FollowListManager
     */
    public function getFollowListManager()
    {
        return $this->get('pon.manager.follow_list');
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
