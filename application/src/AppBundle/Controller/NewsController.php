<?php

namespace AppBundle\Controller;

use CoreBundle\Manager\NewsManager;
use CoreBundle\Manager\StoreManager;
use Faker\Factory;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CoreBundle\Utils\Response as BaseResponse;


class NewsController extends FOSRestController  implements ClassResourceInterface
{
    /**
     * View Detail News
     * @ApiDoc(
     *  section="News",
     *  resource=false,
     *  description="This api is used to view news",
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
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app"}
     * )
     *  @Get("/news/{id}")
     *  @View(serializerGroups={"view_news","list_news_store","list_news_category"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getAction($id)
    {
        $news = $this->getManager()->getNews($id);
        if (!$news) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'news.not_found'
            ));
        }

        return $this->view(BaseResponse::getData($news));
    }

    /**
     * Get List News
     * @ApiDoc(
     *  section="News",
     *  resource=false,
     *  description="This api is used to list news (DONE)",
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
     * @Get("/news")
     * @View(serializerGroups={"list_news","list_news_store","list_news_category"}, serializerEnableMaxDepthChecks=true)
     * @return Response
     */
    public function getNewsListAction(Request $request)
    {
        $params = $request->query->all();
        $result = $this->getManager()->listNews($params);
        return $this->view(BaseResponse::getData($result['data'], $result['pagination']));
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
}
