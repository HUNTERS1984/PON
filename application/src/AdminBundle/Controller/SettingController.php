<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\SettingStoreType;
use AdminBundle\Form\Type\SettingUserType;
use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Store;
use CoreBundle\Entity\StorePhoto;
use CoreBundle\Manager\AppUserManager;
use CoreBundle\Manager\StoreManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{
    /**
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function indexAction(Request $request)
    {
        $appUser = $this->getUser();
        $formUser = $this->createForm(
            SettingUserType::class,
            $appUser)->handleRequest($request);

        if ($request->isXmlHttpRequest() && $formUser->isValid()) {

            /** @var AppUser $appUser */
            $appUser = $formUser->getData();

            if($appUser->getNewPassword() || $appUser->getConfirmPassword()) {
                if($appUser->getNewPassword() != $appUser->getConfirmPassword()) {
                    return $this->getFailureMessage('パスワードの確認は同じパスワードではありません');
                }

                if($appUser->getConfirmPassword()) {
                    $appUser->setPlainPassword($appUser->getConfirmPassword());
                }
            }

            if ($fileUpload = $appUser->getImageFile()) {
                $fileUrl = $this->getManager()->uploadAvatar($fileUpload, $appUser->getAppUserId());
                $appUser->setAvatarUrl($fileUrl);
            }

            $appUser = $this->getManager()->saveAppUser($appUser);

            if (!$appUser) {
                return $this->getFailureMessage('ユーザーの作成に失敗しました');
            }
            return $this->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $formUser->getErrors(true)) > 0) {
            return $this->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        return $this->render(
            'AdminBundle:Setting:index.html.twig',
            [
                'formUser' => $formUser->createView(),
                'appUser' => $appUser
            ]
        );
    }

    /**
     * @return Response
     * @Security("is_granted('ROLE_CLIENT') and not is_granted('ROLE_ADMIN')")
     */
    public function editStoreAction(Request $request)
    {
        /** @var AppUser $appUser */
        $appUser = $this->getUser();
        $store = $appUser->getStore();
        $form = $this->createForm(
            SettingStoreType::class,
            $store)->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var Store $store */
            $store = $form->getData();

            if ($fileUpload = $store->getImageFile()) {
                $fileUrl = $this->getManager()->uploadAvatar($fileUpload, $store->getStoreId());
                $store->setAvatarUrl($fileUrl);
            }

            foreach ($store->getStorePhotos() as $key => $storePhoto) {
                /** @var StorePhoto $storePhoto */
                $photo = $storePhoto->getPhoto();
                if ($photo && $imageFile = $photo->getImageFile()) {
                    $photoId = $this->getStoreManager()->createID('PH');
                    $fileUrl = $this->getStoreManager()->uploadImage($imageFile, $photoId);
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

            $store = $this->getStoreManager()->saveStore($store);

            if (!$store) {
                return $this->getFailureMessage('ユーザーの作成に失敗しました');
            }
            return $this->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        return $this->render(
            'AdminBundle:Setting:store.html.twig',
            [
                'form' => $form->createView(),
                'store' => $store
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
     * @return AppUserManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.app_user');
    }

    /**
     * @return StoreManager
     */
    public function getStoreManager()
    {
        return $this->get('pon.manager.store');
    }
}
