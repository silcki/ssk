<?php

class CalculatorController extends Core_Controller_Action_Abstract
{
    public function preDispatch()
    {
        $docId = $this->AnotherPages->getPageByURL('/calculator/');

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->initPathIDs($docId);

        parent::preDispatch();
    }

    public function indexAction()
    {
        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $docId = $this->AnotherPages->getPageByURL('/calculator/');

        $o_data['ap_id'] = $docId;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocMeta($docId)
            ->getDocInfo($docId)
            ->getDocPath($docId);

        $this->getServiceManager()->getHelper()->getCalculator()
            ->setParams($params)
            ->getCalculators();
    }
}