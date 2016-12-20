<?php

namespace AdminBundle\Controller;

use CoreBundle\Manager\PostManager;
use CoreBundle\Manager\PostPhotoManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CoreBundle\Manager\UseListManager;

class ManagementController extends Controller
{

    /**
     * List all Use_list
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function indexAction(Request $request)
    {
        $params = $request->query->all();
        $params['query'] = isset($params['query']) ? urlencode($params['query']) : '';
        $user = $this->getUser();
        if ($this->isGranted('ROLE_ADMIN')) {
            $result = $this->getManager()->getUseListManagerFromAdmin($params);
        } else {
            $result = $this->getManager()->getUseListManagerFromClient($params, $user);
        }

        return $this->render(
            'AdminBundle:Management:index.html.twig',
            [
                'lists' => $result['data'],
                'pagination' => $result['pagination'],
                'query' => urldecode($params['query']),
                'params' => $params
            ]
        );
    }

    /**
     * Approve Action
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function approveAction($id)
    {
        $useList = $this->getManager()->getUseCouponById($id);
        if(!$this->isGranted('ROLE_ADMIN') &&
            $useList->getAppUser()->getStore()->getId() != $this->getUser()->getStore()->getId()
        ) {
            $useList = null;
        }

        if(!$useList || $useList->getStatus() != 0) {
            throw $this->createNotFoundException('Idが見つかりません。');
        }

        $useList->setStatus(1);
        $this->getManager()->saveUseList($useList);
        return $this->redirectToRoute('admin_management');

    }

    /**
     * View Photo Action
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function viewPhotoAction($id)
    {
        $useList = $this->getManager()->getUseCouponById($id);
        if(!$this->isGranted('ROLE_ADMIN') &&
            $useList->getAppUser()->getStore()->getId() != $this->getUser()->getStore()->getId()
        ) {
            $useList = null;
        }

        if(!$useList || !in_array($useList->getStatus(), [0,1])) {
            throw $this->createNotFoundException('Idが見つかりません。');
        }

        $post = null;
        $postPhotos = [];
        if($useList->getPost()) {
            $post = $this->getPostManager()->getPost($useList->getPost()->getId());
            $postPhotos = $this->getPostPhotoManager()->getPostPhotosByPost($post);
        }

        return $this->render(
            'AdminBundle:Management:photo.html.twig',
            [
                'useList' => $useList,
                'postPhotos' => $postPhotos,
                'post' => $post
            ]
        );

    }


    /**
     * UnApprove Action
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function unApproveAction($id)
    {
        $useList = $this->getManager()->getUseCoupon($id);
        if(!$this->isGranted('ROLE_ADMIN') &&
            $useList->getAppUser()->getStore()->getId() != $this->getUser()->getStore()->getId()
        ) {
            $useList = null;
        }

        if(!$useList || $useList->getStatus() != 1) {
            throw $this->createNotFoundException('Idが見つかりません。');
        }

        $useList->setStatus(0);
        $this->getManager()->saveUseList($useList);
        return $this->redirectToRoute('admin_management');

    }

    /**
     * @return UseListManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.use_list');
    }

    /**
     * @return PostManager
     */
    public function getPostManager()
    {
        return $this->get('pon.manager.post');
    }

    /**
     * @return PostPhotoManager
     */
    public function getPostPhotoManager()
    {
        return $this->get('pon.manager.post_photo');
    }
}
