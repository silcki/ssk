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

        $countryId     = $this->getParam('countryId', 0);
        $scopeId       = $this->getParam('scopeId', 0);
        $productTypeId = $this->getParam('productTypeId', 0);
        $order         = $this->getParam('order', 'order');
        $asc           = $this->getParam('asc', '');

        $o_data['ap_id'] = $docId;
        $o_data['file_name'] = $this->AnotherPages->getDocRealCat($docId);
        $o_data['is_vote'] = '';
        $o_data['asc'] = $asc;
        $o_data['countryId'] = $countryId;
        $o_data['scopeId'] = $scopeId;
        $o_data['productTypeId'] = $productTypeId;

        $this->openData($o_data);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocInfo($docId)
            ->getDocMeta($docId)
            ->getDocPath($docId);

        $whereParams['countryId'] = $countryId;
        $whereParams['scopeId'] = $scopeId;
        $whereParams['productTypeId'] = $productTypeId;
        $whereParams['order'] = $order;
        $whereParams['asc'] = $asc;

        $this->getServiceManager()->getHelper()->getClients()
             ->setParams($params)
             ->getCountry($countryId)
             ->getScope($scopeId)
             ->getProductType($productTypeId)
             ->getClients($whereParams);
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