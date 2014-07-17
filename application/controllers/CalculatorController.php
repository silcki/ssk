<?php

class CalculatorController extends Core_Controller_Action_Abstract
{
    const SECTION_URL = '/calculator/';

    public function preDispatch()
    {
        $docId = $this->AnotherPages->getPageByURL(self::SECTION_URL);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->initPathIDs($docId);

        parent::preDispatch();
    }

    public function indexAction()
    {
        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $docId = $this->AnotherPages->getPageByURL(self::SECTION_URL);

        $indent = $this->getParam('indent', null);

        $o_data['ap_id'] = $docId;
        $o_data['self_url'] = self::SECTION_URL;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocMeta($docId)
            ->getDocInfo($docId)
            ->getDocPath($docId);

        $this->getServiceManager()->getHelper()->getCalculator()
            ->getCalculatorIdByIndent($indent)
            ->setParams($params)
            ->getCalculatorsTabs(self::SECTION_URL)
            ->getCalculator();
    }
}