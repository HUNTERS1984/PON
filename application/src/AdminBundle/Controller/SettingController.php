<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\ContactType;
use AdminBundle\Form\Type\SettingContactType;
use AdminBundle\Form\Type\SettingStoreType;
use AdminBundle\Form\Type\SettingSystemType;
use AdminBundle\Form\Type\SettingUserType;
use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Setting;
use CoreBundle\Entity\Store;
use CoreBundle\Entity\StorePhoto;
use CoreBundle\Manager\AppUserManager;
use CoreBundle\Manager\SettingManager;
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
                    return $this->get('pon.utils.response')->getFailureMessage('account_setting.index.password_invalid');
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
                return $this->get('pon.utils.response')->getFailureMessage('common.status_false.edit');
            }
            return $this->get('pon.utils.response')->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $formUser->getErrors(true)) > 0) {
            return $this->get('pon.utils.response')->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
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
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function systemAction($type, Request $request)
    {
        $contact = $this->getSettingManager()->getSetting($type);
        if(!$contact) {
            $contact = new Setting();
            $contact->setType($type);
        }

        $form = $this->createForm(
            SettingSystemType::class,
            $contact)->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var Setting $contact */
            $contact = $form->getData();
            $contact = $this->getSettingManager()->saveSetting($contact);

            if (!$contact) {
                return $this->get('pon.utils.response')->getFailureMessage('common.status_false.edit');
            }
            return $this->get('pon.utils.response')->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->get('pon.utils.response')->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        $template = "AdminBundle:Setting:setting.html.twig";

        if($type == "term") {
            $template = "AdminBundle:Setting:term.html.twig";
        }

        if($type == "privacy") {
            $template = "AdminBundle:Setting:privacy.html.twig";
        }

        if($type == "trade") {
            $template = "AdminBundle:Setting:trade.html.twig";
        }

        if($type == "hoping") {
            $template = "AdminBundle:Setting:hoping.html.twig";
        }

        return $this->render(
            $template,
            [
                'form' => $form->createView()
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
                $fileUrl = $this->getStoreManager()->uploadAvatar($fileUpload, $store->getStoreId());
                $store->setAvatarUrl($fileUrl);
            }

            if(!empty($store->getStorePhotos())) {
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
            }

            $store = $this->getStoreManager()->saveStore($store);

            if (!$store) {
                return $this->get('pon.utils.response')->getFailureMessage('common.status_false.edit');
            }
            return $this->get('pon.utils.response')->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->get('pon.utils.response')->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
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
     * @return AppUserManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.app_user');
    }

    /**
     * @return SettingManager
    */
    public function getSettingManager()
    {
        return $this->get('pon.manager.setting');
    }

    /**
     * @return StoreManager
     */
    public function getStoreManager()
    {
        return $this->get('pon.manager.store');
    }
}
