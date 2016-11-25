<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\CouponType;
use CoreBundle\Entity\Coupon;
use CoreBundle\Entity\CouponPhoto;
use CoreBundle\Entity\CouponUserPhoto;
use CoreBundle\Entity\Photo;
use CoreBundle\Manager\CouponManager;
use CoreBundle\Manager\CouponPhotoManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SummaryController extends Controller
{
    public function indexAction(Request $request)
    {
        $params = $request->query->all();
        $params['query'] = isset($params['query']) ? $params['query'] : '';
        $result = $this->getManager()->listCoupon($params);

        return $this->render(
            'AdminBundle:Summary:index.html.twig',
            [
                'coupons' => $result['data'],
                'pagination' =>  $result['pagination'],
                'params' => $params
            ]);
    }

    public function editAction(Request $request, $id)
    {
        $coupon = $this->getManager()->getCoupon($id);
        if (!$coupon) {
            throw $this->createNotFoundException('クーポンが見つかりません。');
        }
        $form = $this->createForm(CouponType::class, $coupon)->handleRequest($request);

        if ($request->isXmlHttpRequest() && $form->isValid()) {

            /** @var Coupon $coupon */
            $coupon = $form->getData();
            if ($fileUpload = $coupon->getImageFile()) {
                $fileUrl = $this->getManager()->uploadAvatar($fileUpload, $coupon->getCouponId());
                $coupon->setImageUrl($fileUrl);
            }

            foreach($coupon->getCouponPhotos() as $key=> $couponPhoto) {
                /** @var CouponPhoto $couponPhoto */
                $photo = $couponPhoto->getPhoto();
                if($photo && $imageFile = $photo->getImageFile()) {
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
                    $coupon->getCouponPhotos()->set($key,$couponPhoto);
                }
            }

            foreach($coupon->getCouponUserPhotos() as $key=> $couponUserPhoto) {
                /** @var CouponUserPhoto $couponUserPhoto */
                $photo = $couponUserPhoto->getPhoto();
                if($photo && $imageFile = $photo->getImageFile()) {
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
                    $coupon->getCouponUserPhotos()->set($key,$couponUserPhoto);
                }
            }

            $this->getManager()->saveCoupon($coupon);

            if(!$coupon) {
                return $this->getFailureMessage('クーポンの作成に失敗しました');
            }
            return $this->getSuccessMessage();
        }

        if($request->isXmlHttpRequest() && count($errors = $form->getErrors(true)) > 0) {
            return $this->getFailureMessage($this->get('translator')->trans($errors[0]->getMessage()));
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
     * @param string $message
     * @return Response
     */
    public function getSuccessMessage($message = '')
    {
        return new Response(json_encode(['status'=> true, 'message' => $message]));
    }

    /**
     * @param string $message
     * @return Response
     */
    public function getFailureMessage($message = '')
    {
        return new Response(json_encode(['status'=> false, 'message' => $message]));
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
     * @return CouponPhotoManager
     */
    public function getPhotoManager()
    {
        return $this->get('pon.manager.photo');
    }

}
