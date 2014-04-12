<?php

class DocController extends CommonBaseController
{

    public $order = array();
    public $error = array();
    public $is_post = false;

    function init()
    {
        parent::init();

        $http = new Zend_Controller_Request_Http();

        $this->getSysText('page_main');
        $this->getSysText('form_button_send');

        $action_name = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

        if ($action_name != 'feedbacksuccess') {
            Zend_Controller_Front::getInstance()->getRequest()->setActionName('all');
        }

        $this->getBanners('banner_feedback_form', 6, 11);

        $banner_attach_file = $this->SectionAlign->getBanner(7, 12,
                                                             $this->lang_id);
        if (!empty($banner_attach_file)) {
            $banner_attach_file['DESCRIPTION'] = str_replace("##size##",
                                                             $this->max_post_str(),
                                                             $banner_attach_file['DESCRIPTION']);
            $this->getBannerFromVar('banner_attach_file', $banner_attach_file);
        }

        $res = '';
        if (!empty($this->doc_id))
            $res = $this->AnotherPages->getDocInfo($this->doc_id);

        if ($this->doc_id === false || ($this->doc_id > 0 && empty($res))) {
            $this->page_404();
        }

//      if($res['IS_GROUP_NODE']){
        $this->getSysText('form_email');
        $this->getSysText('form_city');
        $this->getSysText('form_subject');
        $this->getSysText('form_name');
        $this->getSysText('form_lastname');
        $this->getSysText('form_phone');
        $this->getSysText('form_refresh');
        $this->getSysText('form_captcha');
        $this->getSysText('form_feedback');
        $this->getSysText('form_description');
        $this->getSysText('feed_attach');
        $this->getSysText('form_company');
        $this->getSysText('form_fields_error');
//      }
//      $pathIDs = array();
//      $pathIDs = $this->AnotherPages->getParents($this->doc_id);
//      $pathIDs[count($pathIDs)] = $this->doc_id;
//        if ($this->validateSend())
//            $this->feedbacksProcess();
    }

//    private function validateSend()
//    {
//        $http = new Zend_Controller_Request_Http();
//        if (!$http->isPost())
//            return false;
//
////        $caphainp = $http->getPost('captcha');
//
//        echo $_SESSION['biz_captcha'];
//
//        if ($_SESSION['biz_captcha'] !== $http->getPost('captcha'))
//            return false;
//
////        if ($caphainp !== $_SESSION['biz_captcha']) {
////            echo 'Not send';
////
////            return false;
////        } else {
////            echo "send";
////        }
//
//
//
//        $this->order['name'] = $http->getPost('name');
//        $this->order['lastname'] = $http->getPost('lastname');
//        $this->order['phone'] = $http->getPost('phone');
//        $this->order['description'] = $http->getPost('description');
//        $this->order['email'] = $http->getPost('email');
//        $this->order['city'] = $http->getPost('city');
//        $this->order['company'] = $http->getPost('company');
//
//        return true;
//    }

    public function allAction()
    {
        $this->getDocMeta();

        $o_data['ap_id'] = $this->doc_id;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->domXml->set_tag('//data', true);

        if (!empty($this->order)) {
            $this->domXml->create_element('was_send', 1, 2);
            $this->domXml->go_to_parent();
        }

        $this->getDocInfo($this->doc_id);
    }

//    public function feedbackAction()
//    {
//        $this->getDocMeta();
//
//        $o_data['ap_id'] = $this->doc_id;
//        $o_data['is_vote'] = '';
//        $this->openData($o_data);
//
//        if ($this->validateSend()) {
//            $this->feedbacksProcess();
//        }
//
//        $this->getDocInfo($this->doc_id);
//    }
//    private function feedbacksProcess()
//    {
//
//        if (empty($this->error) && $this->is_post) {
//
//            $doc_id = $this->AnotherPages->getPageId('/feedback/');
//            $letter_xml = $this->AnotherPages->getDocXml($doc_id, 0, $this->lang_id);
//
//            $message_admin = $letter_xml;
//
//            if (!empty($this->order['name']))
//                $message_admin = str_replace("##name##", $this->order['name'], $message_admin);
//            else
//                $message_admin = str_replace("##name##", '', $message_admin);
//
//            if (!empty($this->order['lastname']))
//                $message_admin = str_replace("##lastname##", $this->order['lastname'], $message_admin);
//            else
//                $message_admin = str_replace("##lastname##", '', $message_admin);
//
//            if (!empty($this->order['phone']))
//                $message_admin = str_replace("##phone##", $this->order['phone'], $message_admin);
//            else
//                $message_admin = str_replace("##phone##", '', $message_admin);
//
//            if (!empty($this->order['city']))
//                $message_admin = str_replace("##city##", $this->order['city'], $message_admin);
//            else
//                $message_admin = str_replace("##city##", '', $message_admin);
//
//            if (!empty($this->order['company']))
//                $message_admin = str_replace("##company##", $this->order['company'], $message_admin);
//            else
//                $message_admin = str_replace("##company##", '', $message_admin);
//
//            if (!empty($this->order['email']))
//                $message_admin = str_replace("##email##", $this->order['email'], $message_admin);
//            else
//                $message_admin = str_replace("##email##", '', $message_admin);
//
//            if (!empty($this->order['description']))
//                $message_admin = str_replace("##description##", $this->order['description'], $message_admin);
//            else
//                $message_admin = str_replace("##description##", '', $message_admin);
//
//            $message_admin = '<html><head><meta  http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head><body>'
//                    . $message_admin . '</body></html>';
//
//
//            $attach = '';
//            $name = '';
//
//
//            if (!empty($_FILES['feed_attach']['name']) && ($_FILES['feed_attach']['size'] > 0)) {
//                $attach = $_FILES['feed_attach']['tmp_name'];
//                $name = $_FILES['feed_attach']['name'];
//            }
//            $subject = $this->Textes->getSysText('feedback_mail_subject', $this->lang_id);
//
//            $to = $this->getSettingValue('email_feedback');
//            if ($to) {
//                $manager_emails_arr = explode(";", $to);
//                if (!empty($manager_emails_arr)) {
//                    foreach ($manager_emails_arr as $mm) {
//                        $mm = trim($mm);
//                        if (!empty($mm)) {
//                            $this->sendMail2($mm, $message_admin, $subject, $attach, $name, $_FILES['feed_attach']['type']);
//                        }
//                    }
//                }
//            }
//
////        $this->_redirector->gotoUrl('/doc/feedbacksuccess/');
//        } elseif (!empty($this->error) && $this->is_post) {
//            $this->viewErrors($this->error);
//            $this->sendErrorData($this->order);
//        }
//    }
//    function feedbacksuccessAction()
//    {
//
//        $this->getDocMeta();
//
//        $o_data['ap_id'] = $this->doc_id;
//        $o_data['is_vote'] = '';
//        $this->openData($o_data);
//
//        $doc_id = $this->AnotherPages->getPageId('/doc/feedbacksuccess/');
//
//        $info = $this->AnotherPages->getDocInfo($doc_id);
//
//        if ($info) {
//            $this->domXml->create_element('docinfo', '', 2);
//            $this->domXml->set_attribute(array('another_pages_id' => $info['ANOTHER_PAGES_ID']
//                , 'parent_id' => $info['PARENT_ID']
//                    )
//            );
//
//            $this->domXml->create_element('name', $info['NAME']);
//            $this->getDocXml($doc_id, 0, true, $this->lang_id);
//            $this->domXml->go_to_parent();
//        }
//    }

    public function getDocMeta()
    {
        $info = $this->AnotherPages->getDocInfo($this->doc_id, $this->lang_id);
        if ($info) {
            $this->domXml->create_element('docinfo', '', 2);
            $this->domXml->create_element('name', $info['NAME']);

            $this->domXml->create_element('title', $info['TITLE']);

            $descript = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;",
                                     $info['DESCRIPTION']);
            $descript = preg_replace("/\"/", "&#171;", $descript);
            $this->domXml->create_element('description', $descript);

            $keyword = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;",
                                    $info['KEYWORDS']);
            $keyword = preg_replace("/\"/", "&#171;", $keyword);
            $this->domXml->create_element('keywords', $keyword);

            $this->domXml->go_to_parent();
        }
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