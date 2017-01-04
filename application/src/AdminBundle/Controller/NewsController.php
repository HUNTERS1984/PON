<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\NewsType;
use AdminBundle\Form\Type\StoreSearchType;
use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\News;
use CoreBundle\Manager\NewsCategoryManager;
use CoreBundle\Manager\StoreManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Manager\NewsManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NewsController extends Controller
{
    /**
     * List all News
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function indexAction(Request $request)
    {
        $params = $request->query->all();
        $params['query'] = isset($params['query']) ? $params['query'] : '';
        $user = $this->getUser();
        if ($this->isGranted('ROLE_ADMIN')) {
            $result = $this->getManager()->getNewsManagerFromAdmin($params);
        } else {
            $result = $this->getManager()->getNewsManagerFromClient($params, $user);
        }

        return $this->render(
            'AdminBundle:News:index.html.twig',
            [
                'news' => $result['data'],
                'pagination' => $result['pagination'],
                'params' => $params
            ]
        );
    }

    /**
     * Create News Action
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function createAction(Request $request)
    {
        $form = $this->createForm(NewsType::class);

        if($this->isGranted('ROLE_ADMIN')) {
            $form->add('store', StoreSearchType::class, [
                'label' => false,
            ]);
        }

        $form = $form->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var News $news */
            $news = $form->getData();

            if($this->isGranted('ROLE_ADMIN')) {
                $store = $this->getStoreManager()->getStore($news->getStore()->getId());
            }else{
                /** @var AppUser $user */
                $user = $this->getUser();
                $store = $user->getStore();
            }

            if (!$store) {
                return $this->get('pon.utils.response')->getFailureMessage('news.create.store_not_found');
            }

            $news->setStore($store);

            $newsCategory = $this->getNewsCategoryManager()->getNewsCategory($news->getNewsCategory()->getId());

            if(!$newsCategory) {
                return $this->get('pon.utils.response')->getFailureMessage('news.create.news_category_not_found');
            }

            $news->setNewsCategory($newsCategory);


            $news = $this->getManager()->createNews($news);

            if (!$news) {
                return $this->get('pon.utils.response')->getFailureMessage('common.status_false.create');
            }
            return $this->get('pon.utils.response')->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->get('pon.utils.response')->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        return $this->render(
            'AdminBundle:News:create.html.twig',
            [
                'form' => $form->createView()
            ]
        );

    }

    /**
     * Delete Action
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function deleteAction($id)
    {
        $news = $this->getManager()->getNews($id);
        if(!$this->isGranted('ROLE_ADMIN') &&
            $news->getStore()->getId() != $this->getUser()->getStore()->getId()
        ) {
            $news = null;
        }

        if(!$news || $news->getDeletedAt()) {
            return $this->get('pon.utils.response')->getFailureMessage('news.edit.news_not_found');
        }

        $this->getManager()->deleteNews($news);
        return $this->get('pon.utils.response')->getSuccessMessage();

    }

    /**
     * Edit News Action
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function editAction(Request $request, $id)
    {
        $news = $this->getManager()->getNews($id);
        if (!$news) {
            throw $this->createNotFoundException($this->get('translator')->trans('news.edit.news_not_found'));
        }
        $form = $this->createForm(NewsType::class, $news);
        if($this->isGranted('ROLE_ADMIN')) {
            $form->add('store', StoreSearchType::class, [
                'label' => false,
            ]);
        }
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var News $news */
            $news = $form->getData();
            if($this->isGranted('ROLE_ADMIN')) {
                $store = $this->getStoreManager()->getStore($news->getStore()->getId());
            }else{
                /** @var AppUser $user */
                $user = $this->getUser();
                $store = $user->getStore();
            }

            if (!$store) {
                return $this->get('pon.utils.response')->getFailureMessage('news.edit.store_not_found');
            }
            $news->setStore($store);

            $newsCategory = $this->getNewsCategoryManager()->getNewsCategory($news->getNewsCategory()->getId());

            if(!$newsCategory) {
                return $this->get('pon.utils.response')->getFailureMessage('news.edit.news_category_not_found');
            }

            $news->setNewsCategory($newsCategory);

            $news = $this->getManager()->saveNews($news);

            if (!$news) {
                return $this->get('pon.utils.response')->getFailureMessage('common.status_false.edit');
            }
            return $this->get('pon.utils.response')->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->get('pon.utils.response')->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        return $this->render(
            'AdminBundle:News:edit.html.twig',
            [
                'form' => $form->createView(),
                'news' => $news
            ]
        );

    }

    /**
     * @return StoreManager
     */
    public function getStoreManager()
    {
        return $this->get('pon.manager.store');
    }

    /**
     * @return NewsCategoryManager
     */
    public function getNewsCategoryManager()
    {
        return $this->get('pon.manager.news_category');
    }

    /**
     * @return NewsManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.news');
    }
}
