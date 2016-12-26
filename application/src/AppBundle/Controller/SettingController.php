<?php

namespace AppBundle\Controller;

use CoreBundle\Manager\NewsManager;
use CoreBundle\Manager\SettingManager;
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


class SettingController extends FOSRestController  implements ClassResourceInterface
{
    /**
     * View Detail Setting
     * @ApiDoc(
     *  section="Setting",
     *  resource=false,
     *  description="This api is used to view news",
     *  requirements={
     *      {
     *          "name"="type",
     *          "dataType"="string",
     *          "requirement"="\d+",
     *          "description"="type of setting (contact,term,privacy,trade)"
     *      }
     *  },
     *  statusCodes = {
     *     200 = "Returned when successful",
     *     401="Returned when the user is not authorized"
     *   },
     *   views = { "app"}
     * )
     *  @Get("/setting/{type}")
     * @return Response
     */
    public function getAction($type)
    {
        $setting = $this->getManager()->getSetting($type);
        if (!$setting) {
            return $this->view($this->get('pon.exception.exception_handler')->throwError(
                'setting.not_found'
            ));
        }

        return $this->view(BaseResponse::getData($setting));
    }


    /**
     * @return SettingManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.setting');
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }
}
