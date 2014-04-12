<?php
class ProjectsController extends CommonBaseController
{
    public $project_id;

    private $Projects;

    public function init()
    {
        parent::init();

        Zend_Loader::loadClass('Projects');
        $this->Projects = new Projects();

        $this->getSysText('all_projects');
        $this->getSysText('page_main');

        $http = $this->getRequest();

        if ($this->_hasParam('n')) {
            $this->project_id = $this->_getParam('n');
        }
    }

    public function indexAction()
    {
        $ap_id = $this->AnotherPages->getPageId('/projects/index/');
        $perPage = $this->getSettingValue('projects_per_page') ? $this->getSettingValue('projects_per_page') : 15;
        $count = $this->Projects->getProjectsCount($this->project_id);

        $page = $this->_getParam('page', 1);

        $startSelect = ($page - 1) * $perPage;
        $startSelect = $startSelect > $count ? 0 : $startSelect;
        $startSelect = $startSelect < 0 ? 0 : $startSelect;

        $this->getMetaAll($ap_id, $page);

        $o_data['project_id'] = $this->project_id;
        $o_data['ap_id'] = $ap_id;
        $o_data['is_vote'] = '';
        $o_data['file_name'] = $this->AnotherPages->getDocRealCat($ap_id);
        if (!empty($this->template)) {
            $o_data['is_chit'] = 1;
        }
        $this->openData($o_data);

        $this->getDocInfo($ap_id);

        $this->makeSectionInfo($count, $page, $perPage, $o_data['file_name']);

        $this->getProjects($startSelect, $perPage);

        $this->getDocPath($ap_id);
    }

    public function viewAction()
    {
        $ap_id = $this->AnotherPages->getPageId('/projects/index/');
//        $parent_id = $this->AnotherPages->getDocParentId($ap_id);

//        $_href = $this->AnotherPages->getSefURLbyOldURL('/projects/');
//        $path = "//main_menu[url[text()='{$_href}']]";
//
//        Zend_Loader::loadClass('MenuHelper');
//        $menuHelper = new MenuHelper($this->domXml);
//        $menuHelper->setNode($path, 'on_path', '1');

        $this->getMetaSingle();

        $o_data['project_id'] = $this->project_id;
//        $o_data['ap_id'] = $parent_id;
        $o_data['is_vote'] = '';

        $this->openData($o_data);

        $this->getDocInfo($ap_id);

        $this->getProjectsSingle();

        $this->getDocPath($ap_id);
        $this->getGlobalPath();
    }

    private function getMetaSingle()
    {
        $news = $this->Projects->getProjectsSingle($this->project_id, $this->lang_id);
        if (!empty($news)) {
            $this->domXml->create_element('docinfo', '', 2);

            $this->domXml->create_element('name', $news['NAME']);

            $this->domXml->create_element('title', $news['NAME']);
            $this->domXml->create_element('description', $news['NAME']);
            $this->domXml->create_element('keywords', $news['NAME']);

            $this->domXml->go_to_parent();
        }
    }

    private function getMetaAll($ap_id, $page)
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
    public function getProjectsSingle()
    {
        $news = $this->Projects->getProjectsSingle($this->project_id, $this->lang_id);
        if (!empty($news)) {
            $this->domXml->set_tag('//data', true);
            $this->domXml->create_element('projects_single', '', 2);
            $this->domXml->set_attribute(array('news_id' => $news['PROJECTS_ID']));

            $this->domXml->create_element('name', $news['NAME']);
            $this->domXml->create_element('date', $news['date']);

            $this->getDocXml($this->project_id, 11, true, $this->lang_id);
            $this->domXml->go_to_parent();
        }
    }

    private function getProjects($startSelect = 0, $per_page = 0)
    {
        $projects = $this->Projects->getProjects($this->lang_id, $startSelect, $per_page);

        if ($projects) {
            $lang = ($this->lang_id > 0) ?  '/'.$this->lang:'';

            foreach ($projects as $news_view) {
                $is_lang = true;
                $href = '/projects/view/n/' . $news_view['PROJECTS_ID'] . '/';

                $_href = $this->AnotherPages->getSefURLbyOldURL($href);

                if (!empty($_href) && $is_lang) {
                    $href = $lang . $_href;
                } elseif (!empty($_href) && !$is_lang) {
                    $href = $_href;
                }

                $this->domXml->create_element('projects', '', 2);
                $this->domXml->set_attribute(array('news_id' => $news_view['PROJECTS_ID']
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
