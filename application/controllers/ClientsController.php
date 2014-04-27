<?php
class ClientsController extends Core_Controller_Action_Abstract
{
    public function preDispatch()
    {
        $docId = $this->AnotherPages->getPageId('/clients/');

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->initPathIDs($docId);

        parent::preDispatch();
    }

    protected function getSysText()
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

        $docId = $this->AnotherPages->getPageId('/clients/');

        $o_data['ap_id'] = $docId;
        $o_data['is_vote'] = '';

        $this->openData($o_data);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocInfo($docId)
            ->getDocMeta($docId)
            ->getDocPath($docId);

        $this->getServiceManager()->getHelper()->getClients()
             ->setParams($params)
             ->getScope()
             ->getProductType()
             ->getCountry()
             ->getClients();
    }

    public function viewAction()
    {
        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $clientId = $this->getParam('n');

        $docId = $this->AnotherPages->getPageId('/clients/');

        $o_data['ap_id'] = $docId;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocInfo($docId)
            ->getDocMeta($docId)
            ->getDocPath($docId);

        $this->getServiceManager()->getHelper()->getClients()
            ->setParams($params)
            ->getClientData($clientId)
            ->getClientMeta($clientId);
    }
}