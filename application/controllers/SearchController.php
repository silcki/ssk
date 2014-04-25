<?php
class SearchController extends Core_Controller_Action_Abstract
{
    protected function _getSysText()
    {
        $textes = array(
            'page_main',
            'search_catalog',
            'search_items',
            'search_articles',
            'search_news',

        );

        $systemTextes = $this->getServiceManager()->getHelper()->getSystemTextes();
        foreach ($textes as $indent) {
            $systemTextes->getSysText($indent);
        }
    }

    public function indexAction()
    {
        $query = trim($this->getParam('q'));

        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $docId = $this->AnotherPages->getPageId('/search/');

        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocMeta($docId);

        $this->domXml->create_element('query', $query);
        $this->domXml->go_to_parent();

        $page = $this->getParam('page', 1);

        $searchPerPage = $this->getSettingValue('search_per_page') ? $this->getSettingValue('search_per_page') : 25;

        $this->getServiceManager()->getHelper()->getSearch()
            ->setParams($params)
            ->search($query, $page, $searchPerPage);
    }
}