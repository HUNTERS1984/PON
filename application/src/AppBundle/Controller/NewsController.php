<?php

namespace AppBundle\Controller;

use CoreBundle\Entity\News;
use CoreBundle\Entity\Store;
use CoreBundle\Form\Type\NewsType;
use CoreBundle\Manager\NewsManager;
use CoreBundle\Manager\StoreManager;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class NewsController extends FOSRestController  implements ClassResourceInterface
{
    /**
     * Create News
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to create news",
     *  requirements={
     *      {
     *          "name"="store_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of store"
     *      }
     *  },
     *  input={
     *       "class"="CoreBundle\Entity\News",
     *       "groups"={"create_news"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\News",
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

        $storeId = $request->request->get('store_id');
        $store = null;
        if($storeId){
            /**@var StoreManager $storeManager */
            $storeManager = $this->getStoreManager();
            /**@var Store $store*/
            $store = $storeManager->findOneById($storeId);

            if(!$store) {
                $this->get('pon.exception.exception_handler')->throwError(
                    'store.not_found',
                    BadRequestHttpException::class
                );
            }
        }

        $form = $this->createForm(NewsType::class, new News());
        $form->submit($request->request->all());

        /**@var News $news*/
        $news = $form->getData();
        $news->setStore($store);

        $this->get('pon.exception.exception_handler')->validate($news, BadRequestHttpException::class);
        $this->getManager()->createNews($news);
        $news = $this->getSerializer()->serialize($news, ['view','view_news']);

        return $this->view($news, 201);
    }

    /**
     * Update News
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to update news",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of news"
     *      },
     *     {
     *          "name"="store_id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of store"
     *      }
     *  },
     *  input={
     *       "class"="CoreBundle\Entity\News",
     *       "groups"={"create_news"}
     *       },
     *  output={
     *       "class"="CoreBundle\Entity\News",
     *       "groups"={"view"}
     *     },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The News is not found"
     *   }
     * )
     * @return Response
     */
    public function putAction($id ,Request $request)
    {
        $manager = $this->getManager();
        /**@var News $news*/
        $news = $manager->findOneById($id);
        if(!$news) {
            $this->get('pon.exception.exception_handler')->throwError(
                'news.not_found',
                NotFoundHttpException::class
            );
        }

        $storeId = $request->request->get('store_id');
        if($storeId) {
            /**@var StoreManager $storeManager */
            $storeManager = $this->getAppUserManager();
            /**@var Store $store*/
            $store = $storeManager->findOneById($storeId);

            if(!$store) {
                $this->get('pon.exception.exception_handler')->throwError(
                    'store.not_found',
                    BadRequestHttpException::class
                );
            }
            $news->setStore($store);
        }

        $news = $this->get('pon.utils.data')->setData($request->request->all(), $news);
        $this->get('pon.exception.exception_handler')->validate($news, BadRequestHttpException::class);

        $this->getManager()->saveNews($news);
        $news = $this->getSerializer()->serialize($news, ['view','view_news']);
        return $this->view($news, 200);
    }

    /**
     * Delete News
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to delete news",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of news"
     *      }
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The News is not found"
     *   }
     * )
     * @return Response
     */
    public function deleteAction($id)
    {
        /**@var NewsManager $manager*/
        $manager = $this->getManager();
        /**@var News $news*/
        $news = $manager->findOneById($id);
        if(!$news) {
            $this->get('pon.exception.exception_handler')->throwError(
                'news.not_found',
                NotFoundHttpException::class
            );
        }

        $status = $manager->deleteNews($news);
        if(!$status) {
            $this->get('pon.exception.exception_handler')->throwError(
                'news.delete_false',
                BadRequestHttpException::class
            );
        }

        return $this->view(null, 200);
    }

    /**
     * View Detail News
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to view news",
     *  requirements={
     *      {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id of news"
     *      }
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\News",
     *     "groups"={"view"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The News is not found"
     *   }
     * )
     * @return Response
     */
    public function getAction($id)
    {
        $manager = $this->getManager();
        /**@var News $news*/
        $news = $manager->findOneById($id);
        if(!$news || !is_null($news->getDeletedAt())) {
            $this->get('pon.exception.exception_handler')->throwError(
                'news.not_found',
                NotFoundHttpException::class
            );
        }

        $data = $this->getSerializer()->serialize($news, ['view','view_news']);

        return $this->view($data, 200);
    }

    /**
     * Get List News
     * @ApiDoc(
     *  resource=true,
     *  description="This api is used to list news",
     *  parameters={
     *      {"name"="page_size", "dataType"="integer", "required"=false, "description"="page size to return"},
     *      {"name"="page_index", "dataType"="integer", "required"=false, "description"="page index to return"},
     *  },
     *  output={
     *     "class"="CoreBundle\Entity\News",
     *     "groups"={"view"}
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized",
     *     400 = "Returned when the API has invalid input",
     *     404 = "Returned when the The News is not found"
     *   }
     * )
     * @return Response
     */
    public function cgetAction(Request $request)
    {
        $params = $request->query->all();
        $data = $this->getManager()->listNews($params);
        $news = $this->getSerializer()->serialize($data['data'], ['view','view_news']);
        return $this->view($news, 200, $data['pagination']);
    }

    /**
     * @return NewsManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.news');
    }

    /**
     * @return StoreManager
     */
    public function getStoreManager()
    {
        return $this->get('pon.manager.store');
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }
}
