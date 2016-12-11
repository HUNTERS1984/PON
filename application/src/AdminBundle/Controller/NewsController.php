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
                'store_label' => 'ショップ',
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
                return $this->getFailureMessage('店を見つけることができませんでした！');
            }

            $news->setStore($store);

            $newsCategory = $this->getNewsCategoryManager()->getNewsCategory($news->getNewsCategory()->getId());

            if(!$newsCategory) {
                return $this->getFailureMessage('ニュースカテゴリを見つけることができませんでした');
            }

            $news->setNewsCategory($newsCategory);


            $news = $this->getManager()->createNews($news);

            if (!$news) {
                return $this->getFailureMessage('ニュースの作成に失敗しました');
            }
            return $this->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        return $this->render(
            'AdminBundle:News:create.html.twig',
            [
                'form' => $form->createView()
            ]
        );

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
            throw $this->createNotFoundException('ニュースが見つかりませんでした。');
        }
        $form = $this->createForm(NewsType::class, $news);
        if($this->isGranted('ROLE_ADMIN')) {
            $form->add('store', StoreSearchType::class, [
                'label' => false,
                'store_label' => 'ショップ',
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
                return $this->getFailureMessage('店を見つけることができませんでした！');
            }
            $news->setStore($store);

            $newsCategory = $this->getNewsCategoryManager()->getNewsCategory($news->getNewsCategory()->getId());

            if(!$newsCategory) {
                return $this->getFailureMessage('ニュースカテゴリを見つけることができませんでした');
            }

            $news->setNewsCategory($newsCategory);

            $news = $this->getManager()->saveNews($news);

            if (!$news) {
                return $this->getFailureMessage('ニュースの作成に失敗しました');
            }
            return $this->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
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
