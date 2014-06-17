<?php

class CatController extends Core_Controller_Action_Abstract
{
    /**
     * @var HelperCatalogue
     */
    private $_catalogueHelper;

    public function preDispatch()
    {
        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;
        $catalogId = $this->getParam('n', 0);

        $this->_catalogueHelper = $this->getServiceManager()->getHelper()->getCatalogue();
        $this->_catalogueHelper
            ->setParams($params);

        if (!$this->_catalogueHelper->isActive($catalogId)) {
            $this->page_404();
        }

        $this->_catalogueHelper
            ->initPathIDs($catalogId);

        parent::preDispatch();

        $sectionAlignModel = $this->getServiceManager()->getModel()->getSectionAlign();
        if ($this->work_action == 'item') {
            $bannerHelper = $this->getServiceManager()->getHelper()->getBanners();
            $bannerHelper->getBanner('banner_item_form', array('align' => 4, 'section' => 9, 'preg' => 1));

            $banner_attach_file = $sectionAlignModel->getBanner(7, 12, $this->lang_id);
            if (!empty($banner_attach_file)) {
                $banner_attach_file['DESCRIPTION'] = str_replace("##size##", $this->getMaxPostStr(), $banner_attach_file['DESCRIPTION']);

                $bannerHelper->getBannerFromVar('banner_attach_file', $banner_attach_file);
            }
        }

        if (!empty($catalogId)) {
            $articles_per_block = $this->getSettingValue('articles_per_block') ? $this->getSettingValue('articles_per_block') : 3;
            $this->getServiceManager()->getHelper()->getArticles()
                ->setParams($params)
                ->getCatalogArticle($catalogId, $articles_per_block);
        }

        $o_data['cat_id'] = $this->getParam('n', 0);
        $o_data['item_id'] = $this->getParam('it', 0);
        $o_data['is_vote'] = '';
        $this->openData($o_data);
    }

    protected function _getSysText()
    {
        $textes = array(
            'page_main',
            'item_catalog',
            'text_subject_articles',
            'all_articles',
            'form_button_send',
            'text_item_photo',

            'zakaz_stellag',
            'form_email',
            'form_name',
            'form_captcha',
            'form_refresh',
            'form_phone',
            'form_lastname',
            'item_text',
            'form_city',
            'form_description',
            'feed_attach',
            'form_company',
            'form_fields_error',
            'form_back_to_image',
        );

        $systemTextes = $this->getServiceManager()->getHelper()->getSystemTextes();
        foreach ($textes as $indent) {
            $systemTextes->getSysText($indent);
        }
    }

    public function indexAction()
    {
        $catalogId = $this->getParam('n', 0);
        $this->_catalogueHelper
            ->getSubCats($catalogId)
            ->getPath($catalogId)
            ->getCatAllInfo();
    }

    public function viewAction()
    {
        $catalogId = $this->getParam('n');

        $cat_all_name = $this->getServiceManager()->getModel()->getTextes()->getSysText('item_catalog', $this->lang_id);
        $href = '/cat/';
        $_href = $this->AnotherPages->getSefURLbyOldURL($href);
        $href = !empty($_href) ? $_href:$href;
        $befor_path[0]['name'] = $cat_all_name['DESCRIPTION'];
        $befor_path[0]['url'] = $href;

        $this->_catalogueHelper
            ->getCattreeItems($catalogId, 0, true)
            ->getPath($catalogId, $befor_path)
            ->getCatInfo($catalogId);
    }

    public function itemAction()
    {
        $catalogId = $this->_getParam('n', 0);
        $itemId = $this->_getParam('it', 0);

//        if ($this->_catalogueHelper->validateSend($this->requestHttp)) {
//            $formData = $this->_catalogueHelper->getFormData();
//            $formData = $this->formProcessing($formData);
//            $formData['DATA'] = date("Y-m-d H:i:s");
//
//            $this->sendMailToUser($formData);
//            $this->sendMailToAdmin($formData);
//
//            $this->domXml->create_element('was_send', 1, 2);
//            $this->domXml->go_to_parent();
//
//            exit;
//        }

        $children_item_count = $this->_catalogueHelper->getCatalogueModel()->getItemsCount($catalogId);
        $after_path = array();
        if ($children_item_count > 1) {
            $after_path[0]['name'] = $this->_catalogueHelper->getCatalogueModel()->getItemName($itemId, $this->lang_id);
            $after_path[0]['url'] = '';
        }

        $cat_all_name = $this->getServiceManager()->getModel()->getTextes()->getSysText('item_catalog', $this->lang_id);
        $href = '/cat/';
        $_href = $this->AnotherPages->getSefURLbyOldURL($href);
        $href = !empty($_href) ? $_href:$href;
        $befor_path[0]['name'] = $cat_all_name['DESCRIPTION'];
        $befor_path[0]['url'] = $href;

        $this->_catalogueHelper
            ->getItem($itemId)
            ->getItemMeta($itemId)
            ->getPath($catalogId, $befor_path, $after_path)
            ->getSectionInfo($catalogId);
    }

    public function successAction()
    {
        $docId = $this->AnotherPages->getPageId('/cart/success/');
        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocMeta($docId)
            ->getDocInfo($docId)
            ->getDocPath($docId);
    }
}