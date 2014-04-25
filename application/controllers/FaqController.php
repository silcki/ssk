<?php
class FaqController extends Core_Controller_Action_Abstract
{
    protected function _getSysText()
    {
        $textes = array(
            'page_main',
            'say_question',
            'form_email',
            'form_name',
            'form_refresh',
            'form_captcha',
            'faq_text',
            'form_button_send',
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

        $docId = $this->AnotherPages->getPageId('/faq/');

        $o_data['ap_id'] = $docId;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocMeta($docId)
            ->getDocInfo($docId)
            ->getDocPath($docId);

        $this->getServiceManager()->getHelper()->getHelperFaq()
            ->setParams($params)
            ->getMessages();
    }
}