<?php

class ArticlesController extends CommonBaseController
{

    public $article_id;
    public $article_per_page;

    function init()
    {
        parent::init();

        $this->getSysText('all_articles');
        $this->getSysText('page_main');

        $http = new Zend_Controller_Request_Http();

        $action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

        if ($action != 'view')
            Zend_Controller_Front::getInstance()->getRequest()->setActionName('all');
        if ($this->_hasParam('n'))
            $this->article_id = $this->_getParam('n');

        if ($http->isGet()) {
            if ($http->has('page'))
                $this->_setParam('page', $http->getQuery('page'));
            if ($http->has('count'))
                $this->_setParam('count', $http->getQuery('count'));
        }
    }

    public function allAction()
    {
        $ap_id = $this->AnotherPages->getPageId('/articles/all/');

        $this->article_per_page = $this->getSettingValue('article_per_page') ? $this->getSettingValue('article_per_page') : 15;
        $count = $this->Article->getArticleCount($this->article_id);

        $page = $this->_getParam('page');

        if (empty($page)) {
            $page = 1;
        }

        $startSelect = ($page - 1) * $this->article_per_page;
        $startSelect = $startSelect > $count ? 0 : $startSelect;
        $startSelect = $startSelect < 0 ? 0 : $startSelect;

        if ($this->article_id > 0)
            $this->getMetaGroupSingle();
        else
            $this->getMetaAll($ap_id, $page);

        $o_data['article_id'] = $this->article_id;
        $o_data['ap_id'] = $ap_id;
        $o_data['file_name'] = $this->AnotherPages->getDocRealCat($ap_id);
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getDocInfo($ap_id);

//      $this->getArticleGroups();

        $this->makeSectionInfo($count, $page, $this->article_per_page, $o_data['file_name']);

        $this->getArticles($this->article_id, $startSelect, $this->article_per_page);
        $this->getDocPath($ap_id);
    }

    public function viewAction()
    {
        $ap_id = $this->AnotherPages->getPageId('/articles/all/');
        $parent_id = $this->AnotherPages->getDocParentId($ap_id);

        $_href = $this->AnotherPages->getSefURLbyOldURL('/articles/all/');
        $path = "//main_menu[url[text()='{$_href}']]";

        Zend_Loader::loadClass('MenuHelper');
        $menuHelper = new MenuHelper($this->domXml);
        $menuHelper->setNode($path, 'on_path', '1');

        $this->getMetaSingle();

        $o_data['article_id'] = $this->article_id;
        $o_data['ap_id'] = $parent_id;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getDocInfo($ap_id);

        $this->getDocPath($ap_id);
        $this->getGlobalPath();
        $this->getArticleSingle();
    }

    function getMetaGroupSingle()
    {
        $article = $this->Article->getArticleGroupSingle($this->article_id,
                                                         $this->lang_id);
        if (!empty($article)) {
            $this->domXml->create_element('docinfo', '', 2);

            $textes = $this->Textes->getSysText('all_articles', $this->lang_id);

            $href = '/articles/all/';
            $_href = $this->AnotherPages->getSefURLbyOldURL($href);
            if (!empty($_href))
                $href = $_href;

            $this->befor_path[0]['name'] = $textes['DESCRIPTION'];
            $this->befor_path[0]['url'] = $href;

            $this->befor_path[1]['name'] = $article['NAME'];
            $this->befor_path[1]['url'] = '';

            $this->domXml->create_element('name', $article['NAME']);

            $this->domXml->create_element('title', $article['NAME']);
            $this->domXml->create_element('description', $article['NAME']);
            $this->domXml->create_element('keywords', $article['NAME']);

            $this->domXml->go_to_parent();
        }
    }

    function getMetaSingle()
    {
        $article = $this->Article->getArticleSingle($this->article_id,
                                                    $this->lang_id);
        if (!empty($article)) {
            $this->domXml->create_element('docinfo', '', 2);

            $this->domXml->create_element('name', $article['NAME']);

            $this->domXml->create_element('title', $article['NAME']);
            $this->domXml->create_element('description', $article['NAME']);
            $this->domXml->create_element('keywords', $article['NAME']);

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
     * Метод для получения XML подробного текста статьи
     * @access   public
     * @return   string xml
     */
    public function getArticleSingle()
    {
        $article = $this->Article->getArticleSingle($this->article_id,
                                                    $this->lang_id);
        if (!empty($article)) {
            $this->domXml->set_tag('//data', true);
            $this->domXml->create_element('article_single', '', 2);
            $this->domXml->set_attribute(array('article_id' => $article['ARTICLE_ID']));

            $this->domXml->create_element('name', $article['NAME']);
            $this->domXml->create_element('date', $article['date']);

            $this->getDocXml($this->article_id, 5, true, $this->lang_id);
            $this->domXml->go_to_parent();
        }
    }

    /**
     * Метод для получения XML списка статьи
     * @access   public
     * @param    integer $page
     * @param    integer $pageSize
     * @return   string xml
     */
    public function getArticleGroups()
    {
        $article_group = $this->Article->articleGroup($this->lang_id);
        if ($article_group) {
            $this->domXml->set_tag('//data', true);
            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
            else
                $lang = '';

            foreach ($article_group as $view) {
                $on_path = 0;
                if ($this->article_id == $view['ARTICLE_GROUP_ID'])
                    $on_path = 1;

                $href = $lang . '/articles/all/n/' . $view['ARTICLE_GROUP_ID'] . '/';

                $this->domXml->create_element('article_group', '', 2);
                $this->domXml->set_attribute(array('article_group_id' => $view['ARTICLE_GROUP_ID']
                    , 'on_path' => $on_path));

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('url', $href);

                $this->domXml->go_to_parent();
            }
        }
    }

    private function getArticles($article_group_id, $startSelect = 0, $article_per_page = 0)
    {
        $articles = $this->Article->getArticles($article_group_id, $this->lang_id, $startSelect, $article_per_page);
        if ($articles) {
            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
            else
                $lang = '';

            foreach ($articles as $art_view) {
                $this->domXml->create_element('articles', '', 2);
                $this->domXml->set_attribute(array(
                                                'article_id' => $art_view['ARTICLE_ID']
                                              , 'article_group_id' => $art_view['ARTICLE_GROUP_ID']
                                              ));
                $is_lang = false;
                $href = '';
                if (!empty($art_view['URL']) && strpos($art_view['URL'], 'http://') !== false) {
                    $is_lang = true;
                    $href = $art_view['URL'];
                } elseif (!empty($art_view['URL'])) {
                    $href = $art_view['URL'];
                } else {
                    $is_lang = true;
                    $href = '/articles/view/n/' . $art_view['ARTICLE_ID'] . '/';
                }

                $_href = $this->AnotherPages->getSefURLbyOldURL($href);

                if (!empty($_href) && $is_lang)
                    $href = $lang . $_href;
                elseif (!empty($_href) && !$is_lang)
                    $href = $_href;

                $this->domXml->create_element('name', $art_view['NAME']);
                $this->domXml->create_element('descript', $art_view['descript']);
                $this->domXml->create_element('date', $art_view['date']);
                $this->domXml->create_element('url', $href);

                if (!empty($art_view['IMAGE1']) && strchr($art_view['IMAGE1'], "#")) {
                    $tmp = split('#', $art_view['IMAGE1']);
                    $this->domXml->create_element('image1', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0],
                        'w' => $tmp[1],
                        'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }

                $this->domXml->go_to_parent();
            }
        }
    }

}
