<?php

namespace AdminBundle\Controller;

use CoreBundle\Manager\AppUserManager;
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

        $this->getManager()->approveCoupon($useList);
        return $this->redirectToRoute('admin_management');

    }

    /**
     * Approve All Action
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function approveAllAction()
    {
        if($this->isGranted('ROLE_ADMIN')) {
            $this->getManager()->approveAllCouponFromAdmin();
        }else{
            $this->getManager()->approveAllCouponFromClient($this->getUser());
        }

        return $this->getSuccessMessage();

    }

    /**
     * Approve Selected Action
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function approveSelectedAction(Request $request)
    {
        $ids = $request->request->get('ids');
        if(empty($ids)) {
            throw $this->createNotFoundException('Idが見つかりません。');
        }

        $ids = explode(',', $ids);

        if($this->isGranted('ROLE_ADMIN')) {
            $this->getManager()->approveAllCouponFromAdmin($ids);
        }else{
            $this->getManager()->approveAllCouponFromClient($this->getUser(), $ids);
        }

        return $this->getSuccessMessage();

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
     * View Photo Action
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function viewUserAction($id)
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

        $user = $this->getUserManager()->getAppUser($useList->getAppUser()->getId());

        $postPhotos = [];
        $post = null;
        if($useList->getPost()) {
            $post = $this->getPostManager()->getPost($useList->getPost()->getId());
            $postPhotos = $this->getPostPhotoManager()->getPostPhotosByPost($post);
        }

        return $this->render(
            'AdminBundle:Management:user.html.twig',
            [
                'useList' => $useList,
                'user' => $user,
                'post' => $post,
                'postPhotos' => $postPhotos,
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
        $useList = $this->getManager()->getUseCouponById($id);
        if(!$this->isGranted('ROLE_ADMIN') &&
            $useList->getAppUser()->getStore()->getId() != $this->getUser()->getStore()->getId()
        ) {
            $useList = null;
        }

        if(!$useList || $useList->getStatus() != 1) {
            throw $this->createNotFoundException('Idが見つかりません。');
        }

        $this->getManager()->unApproveCoupon($useList);
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
     * @return AppUserManager
    */
    public function getUserManager()
    {
        return $this->get('pon.manager.app_user');
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
}
