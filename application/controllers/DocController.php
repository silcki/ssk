<?php
class DocController extends Core_Controller_Action_Abstract
{
    public function preDispatch()
    {
        $docId = $this->getParam('doc_id', null);
        $res = '';
        if (!empty($docId)) {
            $res = $this->AnotherPages->getDocInfo($docId);
        }

        if (is_null($docId) || empty($res)) {
            $this->page_404();
        }

        parent::preDispatch();

        $sectionAlignModel = $this->getServiceManager()->getModel()->getSectionAlign();

        $bannerHelper = $this->getServiceManager()->getHelper()->getBanners();
        $bannerHelper->getBanner('banner_feedback_form', array('align' => 6, 'section' => 11, 'preg' => 1));

        $banner_attach_file = $sectionAlignModel->getBanner(7, 12, $this->lang_id);
        if (!empty($banner_attach_file)) {
            $banner_attach_file['DESCRIPTION'] = str_replace("##size##", $this->getMaxPostStr(), $banner_attach_file['DESCRIPTION']);

            $bannerHelper->getBannerFromVar('banner_attach_file', $banner_attach_file);
        }

        $this->getSysText();
    }

    private function getSysText()
    {
        $textes = array(
            'page_main',
            'form_button_send',
            'form_email',
            'form_city',
            'form_subject',
            'form_name',
            'form_lastname',
            'form_phone',
            'form_refresh',
            'form_captcha',
            'form_feedback',
            'form_description',
            'feed_attach',
            'form_company',
            'form_fields_error'
        );

        $systemTextes = $this->getServiceManager()->getHelper()->getSystemTextes();
        foreach ($textes as $indent) {
            $systemTextes->getSysText($indent);
        }
    }

    public function indexAction()
    {
        $docId = $this->getParam('doc_id', null);

        $o_data['ap_id'] = $docId;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->domXml->set_tag('//data', true);

        $this->getServiceManager()->getHelper()->getAnotherPages()
             ->getDocMeta($docId)
             ->getDocInfo($docId)
             ->getDocPath($docId);
    }
}