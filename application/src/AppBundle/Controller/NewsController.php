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

        $faker = Factory::create('ja_JP');
        $newsPhotoUrl = [];
        for ($i = 1; $i < $id; $i++) {
            $newsPhotoUrl[] = $faker->imageUrl(640, 480, 'food');
        }

        $introduction = '説明が入ります説明が入ります説明が入ります説明が入り
ます説明が入ります説明が入ります説明が入ります説明が
入ります説明が入ります説明が入ります説明が入ります説
明が入ります説明が入ります説明が入ります説明が入りま
す説明が入ります..説明が入ります説明が入ります説明が
入ります説明が入ります説明が入ります説明が入ります説
明が入ります説明が入ります..説明が入ります説明が入り
ます説明が入ります説明が入ります説明が入ります説明が
入ります説明が入ります説明が入ります';

        $data = [
            'id' => (int)$id,
            'title' => $faker->name,
            'created_at' => new \DateTime(),
            'introduction' => $introduction,
            'shop' => [
                'id' => $faker->numberBetween(1, 200),
                'title' => $faker->company
            ],
            'category' => [
                'id' => $faker->randomElement([1, 2]),
                'name' => $faker->name,
                'icon_url' => $faker->imageUrl(46, 46, 'food')
            ],
            'description' => $introduction,
            'news_photo_url' => $newsPhotoUrl
        ];

        return $this->view(BaseResponse::getData($data));
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

        $faker = Factory::create('ja_JP');
        $data = [];
        $introduction = '説明が入ります説明が入ります説明が入ります説明が入り
ます説明が入ります説明が入ります説明が入ります説明が
入ります説明が入ります説明が入ります説明が入ります説
明が入ります説明が入ります説明が入ります説明が入りま
す説明が入ります..説明が入ります説明が入ります説明が
入ります説明が入ります説明が入ります説明が入ります説
明が入ります説明が入ります..説明が入ります説明が入り
ます説明が入ります説明が入ります説明が入ります説明が
入ります説明が入ります説明が入ります';
        for ($i = 0; $i < 20; $i++) {
            $data[] =
                [
                    'id' => $i + 1,
                    'title' => $faker->name,
                    'created_at' => new \DateTime(),
                    'introduction' => $introduction,
                    'shop' => [
                        'id' => $faker->numberBetween(1, 200),
                        'title' => $faker->company
                    ],
                    'category' => [
                        'id' => $faker->randomElement([1, 2]),
                        'name' => $faker->name,
                        'icon_url' => $faker->imageUrl(46, 46, 'food')
                    ],
                ];
        }

        return $this->view(BaseResponse::getData($data, [
            "limit"=> 20,
            "offset"=> 0,
            "item_total"=> 20,
            "page_total"=> 1,
            "current_page"=> 1,
            "first_page"=> 1,
            "is_first_page"=> true,
            "is_last_page"=> false,
            "last_page"=> 1,
            "next_page"=> 1,
            "previous_page"=> 1,
            "pages"=> [
                1
            ]
        ]));

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
