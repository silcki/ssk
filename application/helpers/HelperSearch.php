<?php
class HelperSearch extends Core_Controller_Action_Helper_Abstract
{
    /**
     * @var AnotherPages
     */
    protected $anotherPages;

    /**
     * @var Catalogue
     */
    protected $catalogue;

    public function init()
    {
        $this->anotherPages = $this->getServiceManager()->getModel()->getAnotherPages();
        $this->catalogue = $this->getServiceManager()->getModel()->getCatalogue();
    }

    /**
     * @param $query
     * @param $page
     * @param $searchPerPage
     */
    public function search($query, $page, $searchPerPage)
    {
        $searchQuery = mb_convert_case($query, MB_CASE_LOWER, 'utf8');

        $indexPath = INDEX_PATH . $this->params['lang'];

        try {
            Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
            $index = Zend_Search_Lucene::open($indexPath);

        } catch (Zend_Search_Lucene_Exception $e) {
            echo $e->getMessage();
        }

        $queryArray = explode(" ", $searchQuery);
        $searchQueryMultiTerm = new Zend_Search_Lucene_Search_Query_MultiTerm();

        foreach ($queryArray as $q) {
            $searchQueryMultiTerm->addTerm(new Zend_Search_Lucene_Index_Term($q), null);
        }

        $hits = $index->find($searchQueryMultiTerm);

        $this->resultToXML($hits, $query, $page, $searchPerPage);
    }

    /**
     * @param $result
     * @param $query
     * @param $page
     * @param $searchPerPage
     */
    private function resultToXML($result, $query, $page, $searchPerPage)
    {
        if (!empty($result)) {
            $paginator = Zend_Paginator::factory($result);
            $paginator->setCurrentPageNumber($page);
            $paginator->setItemCountPerPage($searchPerPage);

            $amount = $paginator->getPages();
            $page = $page > ceil($amount / $searchPerPage) ? ceil($amount / $searchPerPage) : $page;
            $end = ceil($amount / $searchPerPage);

            $this->domXml->set_tag('//data', true);
            $this->domXml->create_element('search_count', $amount, 2);
            $this->domXml->go_to_parent();

            $this->openSection($query, $page, $end, $amount);

            $items = $paginator->getCurrentItems();

            foreach ($items as $hit) {
                $this->domXml->create_element('search_result', "", 2);

                if ($hit->search_section == 'catalog') {
                    $catalog_info = $this->catalogue->getCatInfo($hit->catalogue_id, $this->params['langId']);

                    $href = $hit->url;
                    $_href = $this->anotherPages->getSefURLbyOldURL($href);

                    if (!empty($_href))
                        $href = $_href;

                    $this->domXml->create_element('name', $catalog_info['NAME']);
                    $this->setXmlNode($catalog_info['DESCRIPTION'], 'description');
                    $this->domXml->create_element('href', $href);
                } elseif ($hit->search_section == 'item') {
                    $item_info = $this->catalogue->getItemInfo($hit->item_id, $this->params['langId']);

                    $href = $hit->url;
                    $_href = $this->anotherPages->getSefURLbyOldURL($href);

                    $this->domXml->create_element('name', $item_info['NAME']);

                    $doc = $this->anotherPages->getDocXml($hit->item_id, 3, $this->params['langId']);
                    $doc = stripslashes($doc);

                    $this->setXmlNode($doc, 'description');
                    $this->domXml->create_element('href', $_href);
                } elseif ($hit->search_section == 'another_pages') {
//            $item_info = $this->Item->getItemInfo($hit->item_id, $this->params['langId']);
//
//            $this->domXml->create_element('name',$item_info['NAME']);
//
//            $doc = $this->AnotherPages->getDocXml($hit->item_id,3, $this->params['langId']);
//            $doc = stripslashes($doc);
//
//            $this->setXmlNode($doc,'description');
//            $this->domXml->create_element('url',$hit->url);
                }

                $this->domXml->go_to_parent();
            }
        }
    }

    private function openSection($query = '', $page, $end = 0, $amount = 0)
    {
        $this->domXml->create_element('section', "", 2);
        $this->domXml->set_attribute(array('query' => $query
        , 'page' => $page
        , 'pcount' => $end
        , 'count' => $amount
        ));

        $this->domXml->go_to_parent();
    }
} 