<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Manager\CategoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
class CategoryController extends Controller
{
    /**
     * List all Category
     *
     * @return Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        $params = $request->query->all();
        $params['query'] = isset($params['query']) ? $params['query'] : '';
        $result = $this->getManager()->getCategories($params);

        return $this->render(
            'AdminBundle:Category:index.html.twig',
            [
                'categories' => $result['data'],
                'pagination' => $result['pagination'],
                'params' => $params
            ]
        );
    }

    /**
     * @return CategoryManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.category');
    }
}
