<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\StoreType;
use CoreBundle\Entity\Store;
use CoreBundle\Entity\StorePhoto;
use CoreBundle\Manager\CategoryManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Entity\AppUser;
use CoreBundle\Manager\StoreManager;
use CoreBundle\Serializator\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreController extends Controller
{
    /**
     * Search Store
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
            $result = $this->getManager()->listStoreFromAdmin($params);
        } else{
            $result = $this->getManager()->listStoreFromClient($params, $user);
        }

        $response = new JsonResponse();
        $data = $this->getSerializer()->serialize($result, ['list']);
        return $response->setData($data);
    }

    /**
     * List all Store
     *
     * @return Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function indexAction(Request $request)
    {
        $params = $request->query->all();
        $params['query'] = isset($params['query']) ? $params['query'] : '';
        $result = $this->getManager()->listStoreFromAdmin($params);

        return $this->render(
            'AdminBundle:Store:index.html.twig',
            [
                'stores' => $result['data'],
                'pagination' => $result['pagination'],
                'params' => $params
            ]
        );
    }


    /**
     * Create Store Action
     *
     * @return Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function createAction(Request $request)
    {
        $store = new Store();
        $store->setOperationStartTime(new \DateTime());
        $store->setOperationEndTime(new \DateTime());
        $form = $this->createForm(StoreType::class, $store);

        $form = $form->handleRequest($request);
        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var Store $store */
            $store = $form->getData();
            $category = $this->getCategoryManager()->getCategory($store->getCategory()->getId());
            if (!$category) {
                return $this->get('pon.utils.response')->getFailureMessage('shop.create.category_not_found');
            }

            $store->setCategory($category);
            $store->setStoreId($this->getManager()->createID('ST'));
            if ($fileUpload = $store->getImageFile()) {
                $fileUrl = $this->getManager()->uploadAvatar($fileUpload, $store->getStoreId());
                $store->setAvatarUrl($fileUrl);
            }

            if(!empty($store->getStorePhotos())) {
                foreach ($store->getStorePhotos() as $key => $storePhoto) {
                    /** @var StorePhoto $storePhoto */
                    $photo = $storePhoto->getPhoto();
                    if ($photo && $imageFile = $photo->getImageFile()) {
                        $photoId = $this->getManager()->createID('PH');
                        $fileUrl = $this->getManager()->uploadImage($imageFile, $photoId);
                        $photo
                            ->setPhotoId($photoId)
                            ->setCreatedAt(new \DateTime())
                            ->setUpdatedAt(new \DateTime())
                            ->setImageUrl($fileUrl);
                        $storePhoto->setStore($store);
                    }
                }
            }

            $store = $this->getManager()->createStore($store);

            if (!$store) {
                return $this->get('pon.utils.response')->getFailureMessage('common.status_false.create');
            }
            return $this->get('pon.utils.response')->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->get('pon.utils.response')->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        return $this->render(
            'AdminBundle:Store:create.html.twig',
            [
                'form' => $form->createView()
            ]
        );

    }

    /**
     * Delete Action
     *
     * @return Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteAction($id)
    {
        $store = $this->getManager()->getStore($id);

        if(!$store || $store->getDeletedAt()) {
            return $this->get('pon.utils.response')->getFailureMessage('shop.edit.store_not_found');
        }

        $this->getManager()->deleteStore($store);
        return $this->get('pon.utils.response')->getSuccessMessage();

    }

    /**
     * Edit Store Action
     *
     * @return Response
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function editAction(Request $request, $id)
    {
        $store = $this->getManager()->getStore($id);
        if (!$store) {
            throw $this->createNotFoundException($this->get('translator')->trans('shop.edit.store_not_found'));
        }
        $form = $this->createForm(StoreType::class, $store)->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var Store $store */
            $store = $form->getData();

            if ($fileUpload = $store->getImageFile()) {
                $fileUrl = $this->getManager()->uploadAvatar($fileUpload, $store->getStoreId());
                $store->setAvatarUrl($fileUrl);
            }

            if(!empty($store->getStorePhotos())) {
                foreach ($store->getStorePhotos() as $key => $storePhoto) {
                    /** @var StorePhoto $storePhoto */
                    $photo = $storePhoto->getPhoto();
                    if ($photo && $imageFile = $photo->getImageFile()) {
                        $photoId = $this->getManager()->createID('PH');
                        $fileUrl = $this->getManager()->uploadImage($imageFile, $photoId);
                        $photo
                            ->setPhotoId($photoId)
                            ->setCreatedAt(new \DateTime())
                            ->setUpdatedAt(new \DateTime())
                            ->setImageUrl($fileUrl);
                        $storePhoto
                            ->setPhoto($photo)
                            ->setStore($store);
                        $store->getStorePhotos()->set($key, $storePhoto);
                    }
                }
            }

            $store = $this->getManager()->saveStore($store);

            if (!$store) {
                return $this->get('pon.utils.response')->getFailureMessage('common.status_false.edit');
            }
            return $this->get('pon.utils.response')->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->get('pon.utils.response')->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        return $this->render(
            'AdminBundle:Store:edit.html.twig',
            [
                'form' => $form->createView(),
                'store' => $store
            ]
        );

    }

    /**
     * @return StoreManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.store');
    }

    /**
     * @return CategoryManager
     */
    public function getCategoryManager()
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
