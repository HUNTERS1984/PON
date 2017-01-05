<?php

namespace CustomerBundle\Controller;

use CoreBundle\Manager\SettingManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SettingController extends Controller
{
    public function contactAction()
    {
        $setting = $this->getManager()->getSetting('contact');
        if (!$setting) {
           throw $this->createNotFoundException("連絡先が見つかりませんでした...");
        }

        return $this->render('CustomerBundle:Setting:contact.html.twig',[
            'setting' => $setting
        ]);
    }

    public function privacyAction()
    {
        $setting = $this->getManager()->getSetting('privacy');
        if (!$setting) {
            throw $this->createNotFoundException("プライバシーが見つかりません...");
        }

        return $this->render('CustomerBundle:Setting:privacy.html.twig',[
            'setting' => $setting
        ]);
    }

    public function termAction()
    {
        $setting = $this->getManager()->getSetting('term');
        if (!$setting) {
            throw $this->createNotFoundException("用語が見つかりません...");
        }

        return $this->render('CustomerBundle:Setting:term.html.twig',[
            'setting' => $setting
        ]);
    }

    public function tradeAction()
    {
        $setting = $this->getManager()->getSetting('trade');
        if (!$setting) {
            throw $this->createNotFoundException("取引が見つかりません...");
        }

        return $this->render('CustomerBundle:Setting:trade.html.twig',[
            'setting' => $setting
        ]);
    }

    public function hopingAction()
    {
        $setting = $this->getManager()->getSetting('hoping');
        if (!$setting) {
            throw $this->createNotFoundException("希望が見つかりません...");
        }

        return $this->render('CustomerBundle:Setting:hoping.html.twig',[
            'setting' => $setting
        ]);
    }

    /**
     * @return SettingManager
     */
    public function getManager()
    {
        return $this->get('pon.manager.setting');
    }
}
