<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\CategoryType;
use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Category;
use CoreBundle\Serializator\Serializer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Manager\CategoryManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * Create Category Action
     *
     * @return Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(CategoryType::class);

        $form = $form->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var Category $category */
            $category = $form->getData();
            $category->setCategoryId($this->getManager()->createID('CA'));
            if ($fileUpload = $category->getImageFile()) {
                $fileUrl = $this->getManager()->uploadAvatar($fileUpload, $category->getCategoryId());
                $category->setIconUrl($fileUrl);
            }

            $category = $this->getManager()->createCategory($category);

            if (!$category) {
                return $this->getFailureMessage('カテゴリの作成に失敗しました');
            }
            return $this->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        return $this->render(
            'AdminBundle:Category:create.html.twig',
            [
                'form' => $form->createView()
            ]
        );

    }

    /**
     * Edit Category Action
     *
     * @return Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function editAction(Request $request, $id)
    {
        $category = $this->getManager()->getCategory($id);
        if (!$category) {
            throw $this->createNotFoundException('カテゴリが見つかりませんでした。');
        }
        $form = $this->createForm(CategoryType::class, $category)->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var Category $category */
            $category = $form->getData();

            if ($fileUpload = $category->getImageFile()) {
                $fileUrl = $this->getManager()->uploadAvatar($fileUpload, $category->getCategoryId());
                $category->setIconUrl($fileUrl);
            }

            $category = $this->getManager()->saveCategory($category);

            if (!$category) {
                return $this->getFailureMessage('カテゴリの作成に失敗しました');
            }
            return $this->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        return $this->render(
            'AdminBundle:Category:edit.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category
            ]
        );

    }

    /**
     * Search Category
     *
     * @return Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function searchAction(Request $request)
    {
        /** @var AppUser $user */
        $user = $this->getUser();
        $params = $request->query->all();
        $params['query'] = isset($params['q']) ? $params['q'] : '';
        $result = $this->getManager()->getCategories($params);

        $response = new JsonResponse();
        $data = $this->getSerializer()->serialize($result, ['list']);
        return $response->setData($data);
    }

    /**
     * @param string $message
     * @return Response
     */
    public function getSuccessMessage($message = '')
    {
        return new Response(json_encode(['status' => true, 'message' => $message]));
    }

    /**
     * @param string $message
     * @return Response
     */
    public function getFailureMessage($message = '')
    {
        return new Response(json_encode(['status' => false, 'message' => $message]));
    }

    /**
     * @return CategoryManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.category');
    }

    /**
     * @return Serializer
     */
    public function getSerializer()
    {
        return $this->get('pon.serializator.serializer');
    }
}
