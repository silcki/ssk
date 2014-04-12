<?php

class NewsController extends CommonBaseController
{
    public function init()
    {
        parent::init();
        $this->getSysText('all_news');
        $this->getSysText('page_main');

        $action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

        if ($action != 'view') {
            Zend_Controller_Front::getInstance()->getRequest()->setActionName('all');
        }

    }

    public function allAction()
    {
        $ap_id = $this->AnotherPages->getPageId('/news/all/');
        $news_per_page = $this->getSettingValue('news_per_page') ? $this->getSettingValue('news_per_page') : 15;
        $count = $this->News->getNewsCount(0);

        $page = $this->_getParam('page', 1);

        $startSelect = ($page - 1) * $news_per_page;
        $startSelect = $startSelect > $count ? 0 : $startSelect;
        $startSelect = $startSelect < 0 ? 0 : $startSelect;

//        if ($this->news_id > 0)
//            $this->getMetaGroupSingle();
//        else {
//            $this->getMetaAll($ap_id, $page);
//        }
        $this->getMetaAll($ap_id, $page);

        $o_data['news_id'] = 0;
        $o_data['ap_id'] = $ap_id;
        $o_data['is_vote'] = '';
        $o_data['file_name'] = $this->AnotherPages->getDocRealCat($ap_id);
        if (!empty($this->template)) {
            $o_data['is_chit'] = 1;
        }
        $this->openData($o_data);

        $this->getDocInfo($ap_id);

        $this->makeSectionInfo($count, $page, $news_per_page, $o_data['file_name']);

//      $this->getGroupNews();

        $this->getNews(0, $startSelect, $news_per_page);

        $this->getDocPath($ap_id);
    }

    public function viewAction()
    {
        $ap_id = $this->AnotherPages->getPageId('/news/all/');
        $parent_id = $this->AnotherPages->getDocParentId($ap_id);

        $_href = $this->AnotherPages->getSefURLbyOldURL('/news/all/');
        $path = "//main_menu[url[text()='{$_href}']]";

        Zend_Loader::loadClass('MenuHelper');
        $menuHelper = new MenuHelper($this->domXml);
        $menuHelper->setNode($path, 'on_path', '1');

        $this->getMetaSingle();

        $o_data['news_id'] = $this->_getParam('n');
        $o_data['ap_id'] = $parent_id;
        $o_data['is_vote'] = '';
        if (!empty($this->template)) {
            $o_data['is_chit'] = 1;
        }
        $this->openData($o_data);

        $this->getDocInfo($ap_id);

        $this->getNewsSingle();

        $this->getDocPath($ap_id);
        $this->getGlobalPath();
    }

    function getMetaGroupSingle()
    {
        $news = $this->News->getNewsGroupSingle($this->_getParam('n'), $this->lang_id);
        if (!empty($news)) {
            $this->domXml->create_element('docinfo', '', 2);

            $textes = $this->Textes->getSysText('all_news', $this->lang_id);

            $href = '/news/all/';
            $_href = $this->AnotherPages->getSefURLbyOldURL($href);
            if (!empty($_href))
                $href = $_href;

            $this->befor_path[0]['name'] = $textes['DESCRIPTION'];
            $this->befor_path[0]['url'] = $href;

            $this->befor_path[1]['name'] = $news['NAME'];
            $this->befor_path[1]['url'] = '';

            $this->domXml->create_element('name', $news['NAME']);

            $this->domXml->create_element('title', $news['NAME']);
            $this->domXml->create_element('description', $news['NAME']);
            $this->domXml->create_element('keywords', $news['NAME']);

            $this->domXml->go_to_parent();
        }
    }

    function getMetaSingle()
    {
        $news = $this->News->getNewsSingle($this->_getParam('n'), $this->lang_id);
        if (!empty($news)) {
            $this->domXml->create_element('docinfo', '', 2);

            $this->domXml->create_element('name', $news['NAME']);

            $this->domXml->create_element('title', $news['NAME']);
            $this->domXml->create_element('description', $news['NAME']);
            $this->domXml->create_element('keywords', $news['NAME']);

            $this->domXml->go_to_parent();
        }
    }

    function getMetaAll($ap_id, $page)
    {
        $info = $this->AnotherPages->getDocInfo($ap_id, $this->lang_id);
        if ($info) {
            $this->domXml->create_element('docinfo', '', 2);
            $this->domXml->create_element('name', $info['NAME']);

            $title = $info['TITLE'];
            if ($page > 1) {
                $title.=', стр. '.$page;
            }
            $this->domXml->create_element('title', $title);

            $descript = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;", $info['DESCRIPTION']);
            $descript = preg_replace("/\"/", "&#171;", $descript);
            $this->domXml->create_element('description', $descript);

            $keyword = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;", $info['KEYWORDS']);
            $keyword = preg_replace("/\"/", "&#171;", $keyword);
            $this->domXml->create_element('keywords', $keyword);

            $this->getDocXml($ap_id, 0, true, $this->lang_id);

            $this->domXml->go_to_parent();
        }
    }

    public function makeSectionInfo($count, $page, $pageSize, $fileName)
    {
        $sectionInfo['pcount'] = ceil($count / $pageSize);
        $sectionInfo['count'] = $count;

        $this->domXml->set_tag('//page/data', true);
        $this->domXml->create_element('section', '', 2);
        $cntMiddlePages = 7;
        $cntRightLeft = 3;

        list($relPrev, $relNext) = $this->getNexrPrevRel($page, $sectionInfo['pcount'], $fileName);

        if ($sectionInfo['pcount'] > $cntMiddlePages) {
            $prev = $page - $cntRightLeft;
            if ($prev < 1)
                $start_number = 1;
            elseif (($sectionInfo['pcount'] - $prev) < $cntMiddlePages - 1)
                $start_number = $sectionInfo['pcount'] - $cntMiddlePages - 1;
            else
                $start_number = $prev;

            //$last = $start_number + 10;
            $last = $start_number + $cntRightLeft;
            if ($last > $sectionInfo['pcount'])
                $last = $sectionInfo['pcount'];

            $pages = array();
            for ($i = $start_number; $i <= $last; $i++)
                $pages[] = $i;

            $first_pages = array();
            if ($prev > 1)
                $first_pages[] = '1';
            if ($prev > 2)
                $first_pages[] = '2';
            if ($prev > 3)
                $first_pages[] = '3';

            for ($i = 0; $i < sizeof($first_pages); $i++) {
                if (!in_array($first_pages[$i], $pages)) {
                    $this->domXml->create_element('first_pages', '', 2);
                    $this->domXml->create_element('fpg', $first_pages[$i]);
                    $this->domXml->go_to_parent();
                }
            }

            $last_pages = array($sectionInfo['pcount'] - 2, $sectionInfo['pcount'] - 1, $sectionInfo['pcount']);

            for ($i = 0; $i < sizeof($last_pages); $i++) {
                if (!in_array($last_pages[$i], $pages)) {
                    $this->domXml->create_element('last_pages', '', 2);
                    $this->domXml->create_element('index', $i);
                    $this->domXml->create_element('lpg', $last_pages[$i]);
                    $this->domXml->go_to_parent();
                }
            }
        }
        $this->domXml->set_attribute(array('count' => $sectionInfo['count']
            , 'page' => $page
            , 'pcount' => $sectionInfo['pcount']
            , 'sortId' => ''
            , 'rel_prev' => $relPrev
            , 'rel_next' => $relNext
        ));
        $this->domXml->go_to_parent();
    }

    /**
     * Метод для получения XML подробного текста новости
     * @access   public
     * @return   string xml
     */
    public function getNewsSingle()
    {
        $news = $this->News->getNewsSingle($this->_getParam('n'), $this->lang_id);
        if (!empty($news)) {
            $this->domXml->set_tag('//data', true);
            $this->domXml->create_element('news_single', '', 2);
            $this->domXml->set_attribute(array('news_id' => $news['NEWS_ID']));

            $this->domXml->create_element('name', $news['NAME']);
            $this->domXml->create_element('date', $news['date']);

            $this->getDocXml($this->_getParam('n'), 1, true, $this->lang_id);
            $this->domXml->go_to_parent();
        }
    }

    /**
     * Метод для получения XML списка новостей
     * @access   public
     * @param    integer $page
     * @param    integer $pageSize
     * @return   string xml
     */
    public function getGroupNews()
    {
        $news_group = $this->News->getNewsGroups($this->lang_id);
        if ($news_group) {
            $this->domXml->set_tag('//data', true);
            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
            else
                $lang = '';

            foreach ($news_group as $view) {
                $on_path = 0;
                if ($this->news_id == $view['NEWS_GROUP_ID'])
                    $on_path = 1;

                $this->domXml->create_element('news_group', '', 2);
                $this->domXml->set_attribute(array('news_group_id' => $view['NEWS_GROUP_ID']
                    , 'on_path' => $on_path));

                $href = $lang . '/news/all/n/' . $view['NEWS_GROUP_ID'] . '/';

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('url', $href);

                $this->domXml->go_to_parent();
            }
        }
    }

    private function getNews($news_group_id, $startSelect = 0, $news_per_page = 0)
    {
        $news = $this->News->getNews($news_group_id, $this->lang_id,
                                     $startSelect, $news_per_page);
        if ($news) {
            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
            else
                $lang = '';

            foreach ($news as $news_view) {
                $href = '';
                $is_url = 0;
                $is_lang = false;

                if (!empty($news_view['URL']) && strpos($news_view['URL'], 'http://') !== false) {
                    $is_lang = true;
                    $href = $news_view['URL'];
                } elseif (!empty($news_view['URL'])) {
                    $href = $news_view['URL'];
                    $is_url = 1;
                } else {
                    $is_lang = true;
                    $href = '/news/view/n/' . $news_view['NEWS_ID'] . '/';
                }

                $_href = $this->AnotherPages->getSefURLbyOldURL($href);

                if (!empty($_href) && $is_lang) {
                    $href = $lang . $_href;
                } elseif (!empty($_href) && !$is_lang) {
                    $href = $_href;
                }

                $this->domXml->create_element('news', '', 2);
                $this->domXml->set_attribute(array('news_id' => $news_view['NEWS_ID']
                                                 , 'news_group_id' => $news_view['NEWS_GROUP_ID']
                                                 , 'is_url' => $is_url
                ));

                $this->domXml->create_element('name', $news_view['NAME']);

                $this->domXml->create_element('date', $news_view['date']);
                $this->domXml->create_element('url', $href);
                if (!empty($news_view['IMAGE1']) && strchr($news_view['IMAGE1'],
                                                           "#")) {
                    $tmp = split('#', $news_view['IMAGE1']);
                    $this->domXml->create_element('image1', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0],
                        'w' => $tmp[1],
                        'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }
                $this->setXmlNode($news_view['descript'], 'descript');
                $this->domXml->go_to_parent();
            }
        }
    }

}
