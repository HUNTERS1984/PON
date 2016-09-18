<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\CouponType;
use CoreBundle\Form\Type\CouponTypeType;
use CoreBundle\Manager\CouponTypeManager;
use CoreBundle\Manager\StoreManager;
use CoreBundle\Serializator\Serializer;
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


class CouponTypeController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Create Coupon Type
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to create store type",
     *  input={
     *       "class"="CoreBundle\Entity\CouponType",
     *       "groups"={"create_coupon_type"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\CouponType",
     *       "groups"={"view_coupon_type"}
     *     },
     *  requirements={
     *      {
     *          "name"="store_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of store"
     *      }
     *  },
     *  statusCodes = {
     *     201 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input"
     *   }
     * )
     * @Post("/coupon/types")
     * @return Response
     */
    public function postAction(Request $request)
    {
        $storeId = $request->request->get('store_id');
        $store = null;
        if($storeId){
            /**@var StoreManager $storeManager */
            $storeManager = $this->getStoreManager();
            $store = $storeManager->findOneById($storeId);

            if(!$store) {
                $this->get('pon.exception.exception_handler')->throwError(
                    'store.not_found',
                    BadRequestHttpException::class
                );
            }
        }

        $form = $this->createForm(CouponTypeType::class, new CouponType());
        $form->submit($request->request->all());
        /**@var CouponType $couponType*/
        $couponType = $form->getData();
        $couponType->setStore($store);
        $this->get('pon.exception.exception_handler')->validate($couponType, BadRequestHttpException::class);

        $this->getManager()->createCouponType($couponType);
        $couponType = $this->getSerializer()->serialize($couponType, ['view_coupon_type']);
        return $this->view($couponType, 201);
    }

    /**
     * Update Coupon Type
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to update coupon type",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of coupon type"
     *      },
     *      {
     *          "name"="store_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of store"
     *      },
     *  },
     *  input={
     *       "class"="CoreBundle\Entity\CouponType",
     *       "groups"={"create_coupon_type"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\CouponType",
     *       "groups"={"view_coupon_type"}
     *     },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Coupon Type is not found"
     *   }
     * )
     * @Put("/coupon/types/{id}")
     * @return Response
     */
    public function putAction($id ,Request $request)
    {
        $manager = $this->getManager();
        /**@var CouponType $couponType*/
        $couponType = $manager->findOneById($id);
        if(!$couponType) {
            $this->get('pon.exception.exception_handler')->throwError(
                'coupon_type.not_found',
                NotFoundHttpException::class
            );
        }

        $storeId = $request->request->get('store_id');
        if($storeId) {
            /**@var StoreManager $storeManager */
            $storeManager = $this->getStoreManager();
            $store = $storeManager->findOneById($storeId);

            if(!$store) {
                $this->get('pon.exception.exception_handler')->throwError(
                    'store.not_found',
                    BadRequestHttpException::class
                );
            }
            $couponType->setStore($store);
        }

        $couponType = $this->get('pon.utils.data')->setData($request->request->all(), $couponType);
        $this->get('pon.exception.exception_handler')->validate($couponType, BadRequestHttpException::class);

        $this->getManager()->saveCouponType($couponType);
        $couponType = $this->getSerializer()->serialize($couponType, ['view_coupon_type']);
        return $this->view($couponType, 200);
    }

    /**
     * Delete Coupon Type
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to update coupon type",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of coupon type"
     *      }
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Coupon Type is not found"
     *   }
     * )
     * @Delete("/coupon/types/{id}")
     * @return Response
     */
    public function deleteAction($id)
    {
        $manager = $this->getManager();
        $couponType = $manager->findOneById($id);
        if(!$couponType) {
            $this->get('pon.exception.exception_handler')->throwError(
                'coupon_type.not_found',
                NotFoundHttpException::class
            );
        }

        $status = $manager->deleteCouponType($couponType);
        if(!$status) {
            $this->get('pon.exception.exception_handler')->throwError(
                'coupon_type.delete_false',
                BadRequestHttpException::class
            );
        }

        return $this->view(null, 200);
    }

    /**
     * View Detail Coupon Type
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to view coupon type",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of coupon type"
     *      }
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\CouponType",
     *     "groups"={"view_coupon_type"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Coupon Type is not found"
     *   }
     * )
     * @Get("/coupon/types/{id}")
     * @return Response
     */
    public function getAction($id)
    {
        $manager = $this->getManager();
        /**@var CouponType $couponType*/
        $couponType = $manager->findOneById($id);
        if(!$couponType || !is_null($couponType->getDeletedAt())) {
            $this->get('pon.exception.exception_handler')->throwError(
                'coupon_type.not_found',
                NotFoundHttpException::class
            );
        }

        $data = $this->getSerializer()->serialize($couponType, ['view_coupon_type']);

        return $this->view($data, 200);
    }

    /**
     * Get List Coupon Type
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list coupon type",
     *  parameters={
     *      {"name"="limit", "dataType"="integer", "required"=false, "description"="how many coupon types to return"},
     *      {"name"="offset", "dataType"="integer", "required"=false, "description"="point of coupon types to return"},
     *      {"name"="name", "dataType"="string", "required"=false, "description"="name of coupon type"}
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\CouponType",
     *     "groups"={"view_coupon_type"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Coupon Type is not found"
     *   }
     * )
     * @Get("/coupon/types")
     * @return Response
     */
    public function cgetAction(Request $request)
    {
        $params = $request->query->all();
        $data = $this->getManager()->listCouponType($params);
        $couponTypes = $this->getSerializer()->serialize($data['data'], ['view_coupon_type']);
        return $this->view($couponTypes, 200, $data['pagination']);
    }

    /**
     * @return CouponTypeManager
     */
    public function getManager()
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

    /**
     * @return StoreManager
     */
    public function getStoreManager()
    {
        return $this->get('pon.manager.store');
    }
}
