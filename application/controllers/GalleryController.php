<?php
class GalleryController extends Core_Controller_Action_Abstract
{
    protected function _getSysText()
    {
        $textes = array(
            'page_main',
        );

        $systemTextes = $this->getServiceManager()->getHelper()->getSystemTextes();
        foreach ($textes as $indent) {
            $systemTextes->getSysText($indent);
        }
    }

    public function indexAction()
    {
        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $galleryId = $this->getParam('n', 0);

        $docId = $this->AnotherPages->getPageByURL('/gallery/');
        $_href = $this->AnotherPages->getSefURLbyOldURL('/gallery/');
        $path = "//main_menu[url[text()='{$_href}']]";


        $menuHelper = new Core_Controller_Action_Helper_Menu($this->domXml);
        $menuHelper->setNode($path, 'on_path', '1');

        $o_data['ap_id'] = $docId;
        $o_data['gal_id'] = $galleryId;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocMeta($docId)
            ->getDocPath($docId);

        $this->getServiceManager()->getHelper()->getGallery()
            ->setParams($params)
            ->getGallaryPath($galleryId)
            ->getGalleryGroupMeta($galleryId)
            ->getGalleryGroup($galleryId)
            ->getGalleryLeftMenu($galleryId);
    }

    public function viewAction()
    {
        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $galleryId = $this->getParam('n', 0);

        $galleryHelper = $this->getServiceManager()->getHelper()->getGallery()
                              ->setParams($params);

        if (!$galleryHelper->isActive($galleryId)) {
            $this->page_404();
        }

        $docId = $this->AnotherPages->getPageId('/gallery/');
        $_href = $this->AnotherPages->getSefURLbyOldURL('/gallery/');
        $path = "//main_menu[url[text()='{$_href}']]";

        $menuHelper = new Core_Controller_Action_Helper_Menu($this->domXml);
        $menuHelper->setNode($path, 'on_path', '1');

        $o_data['ap_id'] = $docId;
        $o_data['gal_id'] = $galleryId;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocMeta($docId)
            ->getDocInfo($docId)
            ->getDocPath($docId);

        $galleryHelper
            ->getGalleryGroupMeta($galleryId)
            ->getGallaryPath($galleryId)
            ->getGallery($galleryId)
            ->getGalleryLeftMenu($galleryId);
    }
}