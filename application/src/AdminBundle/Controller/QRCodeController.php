<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\Type\StoreSearchType;
use AdminBundle\Form\Type\StoreType;
use CoreBundle\Entity\Store;
use CoreBundle\Entity\StorePhoto;
use CoreBundle\Manager\CategoryManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CoreBundle\Entity\AppUser;
use CoreBundle\Manager\StoreManager;
use CoreBundle\Serializator\Serializer;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QRCodeController extends Controller
{

    /**
     * Create QRCode Action
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function createAction(Request $request)
    {
        $store = null;
        if(!$this->isGranted('ROLE_ADMIN')) {
            $store = $this->getUser()->getStore();
        }

        $form = $this->createFormBuilder($store)
            ->add('link', TextType::class, [
                'label' => false,
                'attr' => [
                    'class' => 'form-control qrlink',
                    'placeHolder' => '',
                ]
            ]);
        if($this->isGranted('ROLE_ADMIN')) {
            $form->add('store', StoreSearchType::class, [
                'label' => false,
            ]);
        }

        $form = $form->getForm();

        $form = $form->handleRequest($request);
        if ($request->isXmlHttpRequest() && $form->isValid()) {
            $store = $form->getData();

            /** @var Store $store */
            if($this->isGranted('ROLE_ADMIN')) {
                $data = $store;
                $store = $data['store'];
                $link = $data['link'];
                if (!$store) {
                    return $this->get('pon.utils.response')->getFailureMessage('qr_code.create.store_not_found');
                }
                $store = $this->getStoreManager()->getStore($store->getId());
                $store->setLink($link);
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
            'AdminBundle:QRCode:create.html.twig',
            [
                'form' => $form->createView(),
                'store' => $store
            ]
        );

    }

    /**
     * Search Store
     *
     * @return Response
     * @Security("is_granted('ROLE_CLIENT')")
     */
    public function getStoreAction(Request $request)
    {
        $id = $request->get('id');
        /** @var AppUser $user */
        $user = $this->getUser();

        $store = $this->getManager()->getStore($id);

        if(!$this->isGranted('ROLE_ADMIN') && $user->getStore()->getId() !== $store->getId()) {
            return $this->get('pon.utils.response')->getFailureMessage('qr_code.create.store_not_found');
        }
        $data['link'] = $store->getLink();
        $link = $this->get('router')->generate('endroid_qrcode', [
            'text' => $store->getLink() ?? '',
            'extension' => 'png',
            'size' => 200,
            'label' => 'PON',
            'label_font_size' => 16,
        ]);
        $data['qr_link'] = $link;
        return $this->get('pon.utils.response')->getSuccessMessage('Success', $data);
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

    /**
     * @return StoreManager
     */
    public function getStoreManager()
    {
        return $this->get('pon.manager.store');
    }
}
