<?php
class DocController extends Core_Controller_Action_Abstract
{
    public $order = array();
    public $error = array();
    public $is_post = false;

    public function init()
    {
        parent::init();

//        $this->getBanners('banner_feedback_form', 6, 11);
//
//        $banner_attach_file = $this->SectionAlign->getBanner(7, 12, $this->lang_id);
//        if (!empty($banner_attach_file)) {
//            $banner_attach_file['DESCRIPTION'] = str_replace("##size##",
//                                                             $this->max_post_str(),
//                                                             $banner_attach_file['DESCRIPTION']);
//            $this->getBannerFromVar('banner_attach_file', $banner_attach_file);
//        }


    }

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

        $this->getDocMeta();

        $o_data['ap_id'] = $docId;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->domXml->set_tag('//data', true);

        if (!empty($this->order)) {
            $this->domXml->create_element('was_send', 1, 2);
            $this->domXml->go_to_parent();
        }

        $this->getServiceManager()->getHelper()->getAnotherPages()
             ->getDocMeta($docId)
             ->getDocInfo($docId);

        $this->getDocInfo($docId);
    }

    /**
     * Метод для получения информации о странице
     * @access   public
     * @param    integer $id
     * @return   string xml
     */
    public function getDocInfo($id)
    {
        $info = $this->AnotherPages->getDocInfo($id, $this->lang_id);
        if ($info) {
            $this->domXml->set_tag('//data', true);

            $this->domXml->create_element('docinfo', '', 2);
            $this->domXml->set_attribute(array('another_pages_id' => $info['ANOTHER_PAGES_ID']
                , 'parent_id' => $info['PARENT_ID']
                    )
            );

            $this->domXml->create_element('name', $info['NAME']);

            $this->getDocXml($id, 0, true, $this->lang_id);
            $this->domXml->go_to_parent();

            $this->getDocPath($info['ANOTHER_PAGES_ID']);

            $this->domXml->set_tag('//data', true);
            $this->domXml->create_element('sectioninfo', '', 2);

            $image = $this->AnotherPages->getSectionImage($id);

            if (!empty($image) && strchr($image, "#")) {
                $tmp = split('#', $image);
                $this->domXml->create_element('image', '', 2);
                $this->domXml->set_attribute(array('src' => '/images/pg/'.$tmp[0]
                    , 'w' => $tmp[1]
                    , 'h' => $tmp[2]
                ));
                $this->domXml->go_to_parent();
            }
        }
    }

}