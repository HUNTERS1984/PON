<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\Coupon;
use CoreBundle\Form\Type\CouponType;
use CoreBundle\Form\Type\CouponTypeType;
use CoreBundle\Manager\CouponManager;
use CoreBundle\Manager\CouponTypeManager;
use Faker\Factory;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CoreBundle\Utils\Response as BaseResponse;


class CouponController extends FOSRestController  implements ClassResourceInterface
{
    /**
     * Create Coupon
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to create coupon",
     *  requirements={
     *      {
     *          "name"="coupon_type_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of coupon type"
     *      }
     *  },
     *  input={
     *       "class"="CoreBundle\Entity\Coupon",
     *       "groups"={"create_coupon"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\Coupon",
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

        $couponTypeId = $request->request->get('coupon_type_id');
        $couponType = null;
        if($couponTypeId){
            /**@var CouponTypeManager $couponTypeManager */
            $couponTypeManager = $this->getCouponTypeManager();
            $couponType = $couponTypeManager->findOneById($couponTypeId);

            if(!$couponType) {
                return $this->get('pon.exception.exception_handler')->throwError(
                    'coupon_type.not_found'
                );
            }
        }

        $form = $this->createForm(CouponType::class, new Coupon());
        $form->submit($request->request->all());

        /**@var Coupon $coupon*/
        $coupon = $form->getData();
        $coupon->setCouponType($couponType);

        if($error = $this->get('pon.exception.exception_handler')->validate($coupon)) {
            return $error;
        }

        $this->getManager()->createCoupon($coupon);
        $coupon = $this->getSerializer()->serialize($coupon, ['view','view_coupon']);
        return $this->view($coupon, 201);
    }

    /**
     * Update Coupon
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to update coupon",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of coupon"
     *      },
     *     {
     *          "name"="coupon_type_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of coupon type"
     *      }
     *  },
     *  input={
     *       "class"="CoreBundle\Entity\Coupon",
     *       "groups"={"create_coupon"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\Coupon",
     *       "groups"={"view"}
     *     },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Coupon Type is not found"
     *   }
     * )
     * @return Response
     */
    public function putAction($id ,Request $request)
    {
        $manager = $this->getManager();
        /**@var Coupon $coupon*/
        $coupon = $manager->findOneById($id);
        if(!$coupon) {
            $this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_found'
            );
        }

        $couponTypeId = $request->request->get('coupon_type_id');
        if($couponTypeId) {
            /**@var CouponTypeManager $couponTypeManager */
            $couponTypeManager = $this->getCouponTypeManager();
            $couponType = $couponTypeManager->findOneById($couponTypeId);

            if(!$couponType) {
                $this->get('pon.exception.exception_handler')->throwError(
                    'coupon_type.not_found'
                );
            }
            $coupon->setCouponType($couponType);
        }

        $coupon = $this->get('pon.utils.data')->setData($request->request->all(), $coupon);

        if($error = $this->get('pon.exception.exception_handler')->validate($coupon)) {
            return $error;
        }

        $this->getManager()->saveCoupon($coupon);
        $coupon = $this->getSerializer()->serialize($coupon, ['view','view_coupon']);
        return $this->view($coupon, 200);
    }

    /**
     * Delete Coupon
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to delete coupon",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of coupon"
     *      }
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the coupon is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Coupon is not found"
     *   }
     * )
     * @return Response
     */
    public function deleteAction($id)
    {
        /**@var CouponManager $manager*/
        $manager = $this->getManager();
        /**@var Coupon $coupon*/
        $coupon = $manager->findOneById($id);
        if(!$coupon) {
            $this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_found'
            );
        }

        $status = $manager->deleteCoupon($coupon);
        if(!$status) {
            $this->get('pon.exception.exception_handler')->throwError(
                'coupon.delete_false'
            );
        }

        return $this->view(null, 200);
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
     *  output={
     *     "class"="CoreBundle\Entity\Coupon",
     *     "groups"={"view"}
     *  },
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
        $manager = $this->getManager();
        /**@var Coupon $coupon*/
        $coupon = $manager->findOneById($id);
        if(!$coupon || !is_null($coupon->getDeletedAt())) {
            $this->get('pon.exception.exception_handler')->throwError(
                'coupon.not_found'
            );
        }

        $data = $this->getSerializer()->serialize($coupon, ['view','view_coupon']);

        return $this->view($data, 200);
    }

    /**
     * Get List Coupon
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list coupon",
     *  parameters={
     *      {"name"="page_size", "dataType"="integer", "required"=false, "description"="page size to return"},
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"},
     *      {"name"="shop_type", "dataType"="integer", "required"=false, "description"="shop type"},
     *      {"name"="filer", "dataType"="string", "required"=false, "description"="filter of coupon"}
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\Coupon",
     *     "groups"={"view"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Coupon is not found"
     *   }
     * )
     * @return Response
     */
    public function cgetAction(Request $request)
    {
        $faker = Factory::create();
        $data = [];
        for($i=0; $i< 20; $i++) {
            $data[] = [
                'id'        =>  $i+1,
                'title'   => $faker->name,
                'type' => $faker->randomElements([0,1]),
                'expired_time' => time(),
                'image_url' => $faker->imageUrl(),
                'is_like' => $faker->randomElements([0,1]),
                'can_use' => $faker->randomElements([0,1]),
                'code' => $faker->ean13,
                'shop_id' => 1
            ];
        }
        return $this->view(BaseResponse::getData($data));

        $params = $request->query->all();
        $data = $this->getManager()->listCoupon($params);
        $coupons = $this->getSerializer()->serialize($data['data'], ['view','view_coupon']);
        return $this->view($coupons, 200, $data['pagination']);
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
