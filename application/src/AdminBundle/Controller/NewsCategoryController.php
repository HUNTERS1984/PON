<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\NewsCategoryType;
use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\NewsCategory;
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
     * Create News Category Action
     *
     * @return Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(NewsCategoryType::class);

        $form = $form->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var NewsCategory $newsCategory */
            $newsCategory = $form->getData();
            $newsCategory = $this->getManager()->createNewsCategory($newsCategory);

            if (!$newsCategory) {
                return $this->get('pon.utils.response')->getFailureMessage('common.status_false.create');
            }
            return $this->get('pon.utils.response')->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->get('pon.utils.response')->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        return $this->render(
            'AdminBundle:NewsCategory:create.html.twig',
            [
                'form' => $form->createView()
            ]
        );

    }

    /**
     * Edit News Category Action
     *
     * @return Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function editAction(Request $request, $id)
    {
        $newsCategory = $this->getManager()->getNewsCategory($id);
        if (!$newsCategory) {
            throw $this->createNotFoundException($this->get('translator')->trans('news_category.edit.news_category_not_found'));
        }
        $form = $this->createForm(NewsCategoryType::class, $newsCategory)->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var NewsCategory $newsCategory */
            $newsCategory = $form->getData();
            $newsCategory = $this->getManager()->saveNewsCategory($newsCategory);

            if (!$newsCategory) {
                return $this->get('pon.utils.response')->getFailureMessage('common.status_false.edit');
            }
            return $this->get('pon.utils.response')->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->get('pon.utils.response')->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        return $this->render(
            'AdminBundle:NewsCategory:edit.html.twig',
            [
                'form' => $form->createView(),
                'newsCategory' => $newsCategory
            ]
        );

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
