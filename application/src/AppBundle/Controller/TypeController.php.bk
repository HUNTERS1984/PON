<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\Store;
use CoreBundle\Entity\StoreType;
use CoreBundle\Exception\ExceptionHandler;
use CoreBundle\Form\Type\StoreTypeType;
use CoreBundle\Manager\StoreTypeManager;
use CoreBundle\Serializator\Serializer;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class TypeController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Create Store Type
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to create store type",
     *  input={
     *       "class"="CoreBundle\Entity\StoreType",
     *       "groups"={"create"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\StoreType",
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
        $form = $this->createForm(StoreTypeType::class, new StoreType());
        $form->submit($request->request->all());
        $storeType = $form->getData();
        if($error = $this->get('pon.exception.exception_handler')->validate($storeType)) {
            return $error;
        }

        $this->getManager()->createStoreType($storeType);
        return $this->view($storeType, 201);
    }

    /**
     * Update Store Type
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to update store type",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of store type"
     *      }
     *  },
     *  input={
     *       "class"="CoreBundle\Entity\StoreType",
     *       "groups"={"create"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\StoreType",
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
        $storeType = $manager->findOneById($id);
        if(!$storeType) {
            $this->get('pon.exception.exception_handler')->throwError(
                'store_type.not_found'
            );
        }

        $storeType = $this->get('pon.utils.data')->setData($request->request->all(), $storeType);

        if($error = $this->get('pon.exception.exception_handler')->validate($storeType)) {
            return $error;
        }

        $this->getManager()->saveStoreType($storeType);
        return $this->view($storeType, 200);
    }

    /**
     * Delete Store Type
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to update store type",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of store type"
     *      }
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Store Type is not found"
     *   }
     * )
     * @return Response
     */
    public function deleteAction($id)
    {
        $manager = $this->getManager();
        $storeType = $manager->findOneById($id);
        if(!$storeType) {
            $this->get('pon.exception.exception_handler')->throwError(
                'store_type.not_found'
            );
        }

        $status = $manager->deleteStoreType($storeType);
        if(!$status) {
            $this->get('pon.exception.exception_handler')->throwError(
                'store_type.delete_false'
            );
        }

        return $this->view(null, 200);
    }

    /**
     * View Detail Store Type
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to view store type",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of store type"
     *      }
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\StoreType",
     *     "groups"={"view"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Store Type is not found"
     *   }
     * )
     * @return Response
     */
    public function getAction($id)
    {
        $manager = $this->getManager();
        /**@var Store $storeType*/
        $storeType = $manager->findOneById($id);
        if(!$storeType || !is_null($storeType->getDeletedAt())) {
            $this->get('pon.exception.exception_handler')->throwError(
                'store_type.not_found'
            );
        }

        $data = $this->getSerializer()->serialize($storeType, ['view']);

        return $this->view($data, 200);
    }

    /**
     * Get List Store Type
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list store type",
     *  parameters={
     *      {"name"="page_size", "dataType"="integer", "required"=false, "description"="page size to return"},
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"},
     *      {"name"="name", "dataType"="string", "required"=false, "description"="name of store type"}
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\StoreType",
     *     "groups"={"view"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The Store Type is not found"
     *   }
     * )
     * @return Response
     */
    public function cgetAction(Request $request)
    {
        $params = $request->query->all();
        $data = $this->getManager()->listStoreType($params);
        $storeTypes = $this->getSerializer()->serialize($data['data'], ['view']);
        return $this->view($storeTypes, 200, $data['pagination']);
    }

    /**
     * @return StoreTypeManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.store_type');
    }

    /**
     * @return Serializer
    */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }
}
