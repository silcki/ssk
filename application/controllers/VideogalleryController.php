<?php
class VideogalleryController extends Core_Controller_Action_Abstract
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

        $docId = $this->AnotherPages->getPageId('/videogallery/');
        $_href = $this->AnotherPages->getSefURLbyOldURL('/videogallery/');
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

        $this->getServiceManager()->getHelper()->getVideogallery()
            ->setParams($params)
            ->getVideoGalleryGroupMeta($galleryId)
            ->getVideoGallaryPath($galleryId)
            ->getVideoGalleryGroup($galleryId)
            ->getVideoGalleryLeftMenu($galleryId);
    }

    public function viewAction()
    {
        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $galleryId = $this->getParam('n', 0);

        $docId = $this->AnotherPages->getPageId('/videogallery/');
        $_href = $this->AnotherPages->getSefURLbyOldURL('/videogallery/');

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

        $this->getServiceManager()->getHelper()->getVideogallery()
            ->setParams($params)
            ->getVideoGalleryGroupMeta($galleryId)
            ->getVideoGallaryPath($galleryId)
            ->getVideoGallery($galleryId)
            ->getVideoGalleryLeftMenu($galleryId);
    }

    public function tubeAction()
    {
        $this->_disableRender();

        $galleryId = $this->getParam('n', 0);
        echo $this->getServiceManager()->getHelper()->getVideogallery()->getVideoCode($galleryId);
    }

    public function videoAction()
    {
        $galleryId = $this->getParam('n', 0);

        $file = $this->getServiceManager()->getHelper()->getVideogallery()->getVideoFile($galleryId);
        $video_file = explode('#', $file);
        $this->domXml->set_tag('//pade', true);
        $this->domXml->create_element('video_file', $video_file[0], 1);
    }
}