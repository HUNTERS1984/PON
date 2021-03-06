<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\CouponType;
use AdminBundle\Form\Type\StoreSearchType;
use CoreBundle\Entity\AppUser;
use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\CouponPhoto;
use CoreBundle\Entity\CouponUserPhoto;
use CoreBundle\Entity\Photo;
use CoreBundle\Manager\CouponManager;
use CoreBundle\Manager\CouponPhotoManager;
use CoreBundle\Manager\StoreManager;
use Doctrine\Common\Collections\ArrayCollection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SummaryController extends Controller
{
    /**
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function indexAction(Request $request)
    {
        $params = $request->query->all();
        $params['query'] = isset($params['query']) ? $params['query'] : '';

        if($this->isGranted('ROLE_ADMIN')) {
            $result = $this->getManager()->listCouponFromAdmin($params);
        } else{
            /** @var AppUser $user */
            $user = $this->getUser();
            $result = $this->getManager()->listCouponFromClient($params, $user);
        }

        return $this->render(
            'AdminBundle:Summary:index.html.twig',
            [
                'coupons' => $result['data'],
                'pagination' => $result['pagination'],
                'params' => $params
            ]);
    }

    /**
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function createAction(Request $request)
    {
        $coupon = new Coupon();
        $expiredTime = new \DateTime();
        $coupon->setExpiredTime($expiredTime);
        $form = $this->createForm(CouponType::class, $coupon);

        $form = $form->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var Coupon $coupon */
            $coupon = $form->getData();

            $store = $this->getStoreManager()->getStore($coupon->getStore()->getId());

            if (!$store) {
                return $this->get('pon.utils.response')->getFailureMessage('coupon_list.create.store_not_found');
            }

            $coupon->setStore($store);
            $coupon->setCouponId($this->getManager()->createID('CO'));

            if ($fileUpload = $coupon->getImageFile()) {
                $fileUrl = $this->getManager()->uploadAvatar($fileUpload, $coupon->getCouponId());
                $coupon->setImageUrl($fileUrl);
            }

            if(!empty($coupon->getCouponPhotos())) {
                foreach ($coupon->getCouponPhotos() as $key => $couponPhoto) {
                    /** @var CouponPhoto $couponPhoto */
                    $photo = $couponPhoto->getPhoto();
                    if ($photo && $imageFile = $photo->getImageFile()) {
                        $photoId = $this->getManager()->createID('PH');
                        $fileUrl = $this->getManager()->uploadImage($imageFile, $photoId);
                        $photo
                            ->setPhotoId($photoId)
                            ->setCreatedAt(new \DateTime())
                            ->setUpdatedAt(new \DateTime())
                            ->setImageUrl($fileUrl);
                        $couponPhoto->setCoupon($coupon);
                    }
                }
            }

            if(!empty($coupon->getCouponUserPhotos())) {
                foreach ($coupon->getCouponUserPhotos() as $key => $couponUserPhoto) {
                    /** @var CouponUserPhoto $couponUserPhoto */
                    $photo = $couponUserPhoto->getPhoto();
                    if ($photo && $imageFile = $photo->getImageFile()) {
                        $photoId = $this->getManager()->createID('PH');
                        $fileUrl = $this->getManager()->uploadImage($imageFile, $photoId);
                        $photo
                            ->setPhotoId($photoId)
                            ->setCreatedAt(new \DateTime())
                            ->setUpdatedAt(new \DateTime())
                            ->setImageUrl($fileUrl);
                        $couponUserPhoto->setCoupon($coupon);
                    }
                }
            }

            $expiredTime = $coupon->getExpiredTime();
            $expiredTime->setTime(23, 59, 59);
            $coupon->setExpiredTime($expiredTime);

            $this->getManager()->createCoupon($coupon);

            if (!$coupon) {
                return $this->get('pon.utils.response')->getFailureMessage('common.status_false.create');
            }
            return $this->get('pon.utils.response')->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->get('pon.utils.response')->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        return $this->render(
            'AdminBundle:Summary:create.html.twig',
            [
                'form' => $form->createView()
            ]
        );

    }

    /**
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function editAction(Request $request, $id)
    {
        $coupon = $this->getManager()->getCoupon($id);
        if (!$coupon) {
            throw $this->createNotFoundException($this->get('translator')->trans('coupon_list.edit.coupon_not_found'));
        }

        $form = $this->createForm(CouponType::class, $coupon);

        if ($this->isGranted('ROLE_ADMIN')) {
            $form
                ->add('store', StoreSearchType::class, [
                    'label' => false,
                ]);
        }

        $form = $form->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var Coupon $coupon */
            $coupon = $form->getData();

            $store = $this->getStoreManager()->getStore($coupon->getStore()->getId());

            if (!$store) {
                return $this->get('pon.utils.response')->getFailureMessage('coupon_list.edit.store_not_found');
            }

            $coupon->setStore($store);

            if ($fileUpload = $coupon->getImageFile()) {
                $fileUrl = $this->getManager()->uploadAvatar($fileUpload, $coupon->getCouponId());
                $coupon->setImageUrl($fileUrl);
            }

            foreach ($coupon->getCouponPhotos() as $key => $couponPhoto) {
                /** @var CouponPhoto $couponPhoto */
                $photo = $couponPhoto->getPhoto();
                if ($photo && $imageFile = $photo->getImageFile()) {
                    $photoId = $this->getManager()->createID('PH');
                    $fileUrl = $this->getManager()->uploadImage($imageFile, $photoId);
                    $photo
                        ->setPhotoId($photoId)
                        ->setCreatedAt(new \DateTime())
                        ->setUpdatedAt(new \DateTime())
                        ->setImageUrl($fileUrl);
                    $couponPhoto
                        ->setPhoto($photo)
                        ->setCoupon($coupon);
                    $coupon->getCouponPhotos()->set($key, $couponPhoto);
                }
            }

            foreach ($coupon->getCouponUserPhotos() as $key => $couponUserPhoto) {
                /** @var CouponUserPhoto $couponUserPhoto */
                $photo = $couponUserPhoto->getPhoto();
                if ($photo && $imageFile = $photo->getImageFile()) {
                    $photoId = $this->getManager()->createID('PH');
                    $fileUrl = $this->getManager()->uploadImage($imageFile, $photoId);
                    $photo
                        ->setPhotoId($photoId)
                        ->setCreatedAt(new \DateTime())
                        ->setUpdatedAt(new \DateTime())
                        ->setImageUrl($fileUrl);
                    $couponUserPhoto
                        ->setPhoto($photo)
                        ->setCoupon($coupon);
                    $coupon->getCouponUserPhotos()->set($key, $couponUserPhoto);
                }
            }

            $expiredTime = $coupon->getExpiredTime();
            $expiredTime->setTime(23, 59, 59);
            $coupon->setExpiredTime($expiredTime);

            $coupon = $this->getManager()->saveCoupon($coupon);

            if (!$coupon) {
                return $this->get('pon.utils.response')->getFailureMessage('common.status_false.edit');
            }
            return $this->get('pon.utils.response')->getSuccessMessage();
        }

        if ($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->get('pon.utils.response')->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
        }

        return $this->render(
            'AdminBundle:Summary:edit.html.twig',
            [
                'form' => $form->createView(),
                'coupon' => $coupon
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
        $coupon = $this->getManager()->getCoupon($id);
        if(!$this->isGranted('ROLE_ADMIN') &&
            $coupon->getStore()->getId() != $this->getUser()->getStore()->getId()
        ) {
            $coupon = null;
        }

        if(!$coupon || $coupon->getDeletedAt()) {
            return $this->get('pon.utils.response')->getFailureMessage('coupon_list.edit.coupon_not_found');
        }

        $this->getManager()->deleteCoupon($coupon);
        return $this->get('pon.utils.response')->getSuccessMessage();

    }

    /**
     * @return CouponManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.coupon');
    }

    /**
     * @return CouponPhotoManager
     */
    public function getCouponPhotoManager()
    {
        return $this->get('pon.manager.coupon_photo');
    }

    /**
     * @return StoreManager
     */
    public function getStoreManager()
    {
        return $this->get('pon.manager.store');
    }

    /**
     * @return CouponPhotoManager
     */
    public function getPhotoManager()
    {
        return $this->get('pon.manager.photo');
    }

}
