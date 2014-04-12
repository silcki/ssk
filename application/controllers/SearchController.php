<?php

require_once ZEND_PATH . '/Search/Lucene/Analysis/Analyzer.php';
require_once ZEND_PATH . '/Search/Lucene/Search/QueryParser.php';

class SearchController extends CommonBaseController
{

    public $search;
    public $search_per_page;
    public $query;
    public $indexPath;

    function init()
    {
        parent::init();
        $this->getSysText('page_main');
        $this->getSysText('search_catalog');
        $this->getSysText('search_items');
        $this->getSysText('search_articles');
        $this->getSysText('search_news');

        $this->search_per_page = $this->getSettingValue('search_per_page') ? $this->getSettingValue('search_per_page') : 25;

        require_once ZEND_PATH . '/Search/Lucene.php';
        $this->query = trim($this->_getParam('q'));
        $this->indexPath = INDEX_PATH . $this->lang;
    }

    public function allAction()
    {
        $ap_id = $this->AnotherPages->getPageId('/search/');
        $this->getMetaAll($ap_id);

        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->domXml->create_element('query', $this->query);
        $this->domXml->go_to_parent();

        $result = $this->search(mb_convert_case($this->query, MB_CASE_LOWER,
                                                'utf8'));
        $this->resultToXML($result);
    }

    function getMetaAll($ap_id)
    {
        $info = $this->AnotherPages->getDocInfo($ap_id, $this->lang_id);
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

    private function resultToXML($result)
    {
        if (!empty($result)) {
            $page = $this->_getParam('page', 1);
            require_once ZEND_PATH . '/Paginator.php';
            $paginator = Zend_Paginator::factory($result);
            $paginator->setCurrentPageNumber($page);
            $paginator->setItemCountPerPage($this->search_per_page);


            $amount = $paginator->getPages()->totalItemCount;
            $page = $page > ceil($amount / $this->search_per_page) ? ceil($amount / $this->search_per_page) : $page;
            $end = ceil($amount / $this->search_per_page);

            $this->domXml->set_tag('//data', true);
            $this->domXml->create_element('search_count', $amount, 2);
            $this->domXml->go_to_parent();

            $this->openSection($this->query, $page, $end, $amount);

            $items = $paginator->getCurrentItems();

            foreach ($items as $hit) {
                $this->domXml->create_element('search_result', "", 2);

                if ($hit->search_section == 'catalog') {
                    $catalog_info = $this->Catalogue->getCatInfo($hit->catalogue_id,
                                                                 $this->lang_id);

                    $href = $hit->url;
                    $_href = $this->AnotherPages->getSefURLbyOldURL($href);

                    if (!empty($_href))
                        $href = $_href;

                    $this->domXml->create_element('name', $catalog_info['NAME']);
                    $this->setXmlNode($catalog_info['DESCRIPTION'],
                                      'description');
                    $this->domXml->create_element('href', $href);
                }
                elseif ($hit->search_section == 'item') {
                    $item_info = $this->Catalogue->getItemInfo($hit->item_id,
                                                               $this->lang_id);

                    $href = $hit->url;
                    $_href = $this->AnotherPages->getSefURLbyOldURL($href);

                    if (!empty($_href))
                        $href = $_href;

                    $this->domXml->create_element('name', $item_info['NAME']);

                    $doc = $this->AnotherPages->getDocXml($hit->item_id, 3,
                                                          $this->lang_id);
                    $doc = stripslashes($doc);

                    $this->setXmlNode($doc, 'description');
                    $this->domXml->create_element('href', $_href);
                }
                elseif ($hit->search_section == 'another_pages') {
//            $item_info = $this->Item->getItemInfo($hit->item_id, $this->lang_id);
//
//            $this->domXml->create_element('name',$item_info['NAME']);
//
//            $doc = $this->AnotherPages->getDocXml($hit->item_id,3, $this->lang_id);
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

    public function search($query)
    {
        try {
            Zend_Search_Lucene_Search_QueryParser::setDefaultEncoding('utf-8');
            $index = Zend_Search_Lucene::open($this->indexPath);
        } catch (Zend_Search_Lucene_Exception $e) {
            echo "Ошибка:{$e->getMessage()}";
            return false;
        }

//      $userQuery = Zend_Search_Lucene_Search_QueryParser::parse($query);
//     $hits = $index->find($userQuery);
//    $term  = new Zend_Search_Lucene_Index_Term($query);
//    $query = new Zend_Search_Lucene_Search_Query_Term($term);
//    $hits  = $index->find($query);



        $queryArray = explode(" ", $query);
//    $query = new Zend_Search_Lucene_Search_Query_Phrase();
        $query = new Zend_Search_Lucene_Search_Query_MultiTerm();
        foreach ($queryArray as $q) {
            $query->addTerm(new Zend_Search_Lucene_Index_Term($q), null);
        }
//    $query->setSlop(3);
        $hits = $index->find($query);

//print_r($hits);die;



        return $hits;
    }

}