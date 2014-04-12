<?php

class FaqController extends CommonBaseController
{

    public $Faq;
    public $question = array();
    public $error = array();
    public $is_post = false;

    function init()
    {
        parent::init();

        $http = new Zend_Controller_Request_Http();
        $this->_redirector = $this->_helper->getHelper('Redirector');
        $this->getSysText('page_main');

        Zend_Loader::loadClass('Faq');
        $this->Faq = new Faq();

        $action_name = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        if ($action_name != 'send' && $action_name != 'success') {
            Zend_Controller_Front::getInstance()->getRequest()->setActionName('all');
        }

        $this->getSysText('say_question');
        $this->getSysText('form_name');
        $this->getSysText('form_email');
        $this->getSysText('form_refresh');
        $this->getSysText('form_captcha');
        $this->getSysText('faq_text');
        $this->getSysText('form_button_send');

        if ($http->isPost()) {
            $this->question['name'] = $http->getPost('name');
            $this->question['faq_text'] = $http->getPost('faq_text');
            $this->question['email'] = $http->getPost('email');
            $this->sendData();
        }
    }

    public function allAction()
    {
        $ap_id = $this->AnotherPages->getPageId('/faq/');

        $this->getDocMeta($ap_id);

        $o_data['ap_id'] = $ap_id;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        if (!empty($this->question)) {
            $this->domXml->create_element('was_send', 1, 2);
            $this->domXml->go_to_parent();
        }

        $this->getDocInfo($ap_id);

//      $this->getGlobalPath();
        $this->getDocPath($ap_id);

        $this->getMessages();
    }

    private function getDocMeta($id)
    {
        $info = $this->AnotherPages->getDocInfo($id, $this->lang_id);
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

            $this->getDocXml($id, 0, true, $this->lang_id);

            $this->domXml->go_to_parent();
        }
    }

    public function getMessages()
    {
        $this->domXml->set_tag('//data', true);
        $messages_group = $this->Faq->getGroupMessage($this->lang_id);
        if (!empty($messages_group)) {
            foreach ($messages_group as $view) {
                $this->domXml->create_element('faq_group', '', 2);
                $this->domXml->set_attribute(array('id' => $view['QUESTION_GROUP_ID']));

                $this->domXml->create_element('name', $view['NAME']);

                $messages = $this->Faq->getMessage($view['QUESTION_GROUP_ID'],
                                                   $this->lang_id);
                if (!empty($messages)) {
                    foreach ($messages as $view) {
                        $this->domXml->create_element('faq', '', 2);
                        $this->domXml->set_attribute(array('question_id' => $view['QUESTION_ID']));

                        $this->domXml->create_element('question',
                                                      $view['QUESTION']);
                        $this->setXmlNode($view['ANSWER'], 'answer');

                        $this->domXml->go_to_parent();
                    }
                }

                $this->domXml->go_to_parent();
            }
        }
    }

    private function sendData()
    {
        $ap_id = $this->AnotherPages->getPageId('/faq/');

        $doc_id = $this->AnotherPages->getPageId('/faq/send/');
        $letter_xml = $this->AnotherPages->getDocXml($doc_id, 0, $this->lang_id);

        $message_admin = $letter_xml;

        if (!empty($this->question ['name']))
            $message_admin = str_replace("##name##", $this->question ['name'],
                                         $message_admin);
        else
            $message_admin = str_replace("##name##", '', $message_admin);

        if (!empty($this->question['email']))
            $message_admin = str_replace("##email##", $this->question['email'],
                                         $message_admin);
        else
            $message_admin = str_replace("##email##", '', $message_admin);

        if (!empty($this->question['faq_text']))
            $message_admin = str_replace("##description##",
                                         $this->question['faq_text'],
                                         $message_admin);
        else
            $message_admin = str_replace("##description##", '', $message_admin);

        $message_admin = '<html><head><meta  http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head><body>'
                . $message_admin . '</body></html>';

        $to = $this->getSettingValue('email_faq');

        $subject = 'Часто задаваемые вопросы';
        $this->sendMail2($to, $message_admin, $subject);
    }

}