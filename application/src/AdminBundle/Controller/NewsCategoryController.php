<?php

namespace AdminBundle\Controller;

use CoreBundle\Entity\AppUser;
use CoreBundle\Serializator\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Manager\NewsCategoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class NewsCategoryController extends Controller
{
    /**
     * List all Category
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function indexAction(Request $request)
    {
        $params = $request->query->all();
        $params['query'] = isset($params['query']) ? $params['query'] : '';
        $result = $this->getManager()->getNewsCategories($params);

        return $this->render(
            'AdminBundle:NewsCategory:index.html.twig',
            [
                'new_categories' => $result['data'],
                'pagination' => $result['pagination'],
                'params' => $params
            ]
        );
    }

    /**
     * Search News Category
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function searchAction(Request $request)
    {
        /** @var AppUser $user */
        $user = $this->getUser();
        $params = $request->query->all();
        $params['query'] = isset($params['q']) ? $params['q'] : '';
        if($this->isGranted('ROLE_ADMIN')) {
            $result = $this->getManager()->listNewsCategoryFromAdmin($params);
        } else{
            $result = $this->getManager()->listNewsCategoryFromClient($params, $user);
        }

        $response = new JsonResponse();
        $data = $this->getSerializer()->serialize($result, ['list']);
        return $response->setData($data);
    }

    /**
     * @return NewsCategoryManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.news_category');
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }
}
