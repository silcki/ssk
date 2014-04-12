<?php

/* vim: set expandtab tabstop=4 shiftwidth=4: */
// +----------------------------------------------------------------------+
// | PHP Version 5                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 2008 adlabs.com.ua                                     |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// | Authors: dev@adlabs.com.ua                                           |
// |                                                                      |
// +----------------------------------------------------------------------+
//
// $Id: CommonBaseController.php,v 1.1  Exp $
//
// Базовый контроллер для формирования XML шаблонов,используемых на страницах новостей, статей, внутренних страницах,регистрации (всех кроме каталога и корзины)
//
//
require ZEND_PATH . '/Controller/Action.php';
require ROOT_PATH . '/include/UriDecorator.class.php';

function convertSize($size)
{
    $unit = array('b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb', 'Eb', 'Zb', 'Yb');
    return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' '. $unit[$i];
}

function addSizeText($matches)
{
    $_temp = $_SERVER['DOCUMENT_ROOT'] . $matches[2];
    $size = '';
    if (is_file($_temp)) {
        $size = convertSize(filesize($_temp));
        return "<a " . $matches[1] . " href=\"" . $matches[2] . " size={$size}\"";
    } else {
        if (strpos($matches[2], 'http') !== false) {
            if (strpos($matches[2], 'redirect') !== false) {
                return "<a " . $matches[1] . " href=\"" . urlencode($matches[2]) . "\"";
            }
        }
    }

    return "<a " . $matches[1] . " href=\"" . $matches[2] . "\"";
}

abstract class CommonBaseController extends Zend_Controller_Action
{
    public $domXml;
    public $base;
    public $News;
    public $Article;
    public $Clients;
    public $AnotherPages;
    public $Brands;
    public $Catalogue;
    public $SystemSets;
    public $GoodsGroup;
    public $SectionAlign;
    public $Item;
    public $Vopros;
    public $Textes;
    public $Gallery;
    public $FileTypes;
    public $site_vote;
    private $print;
    public $lang;
    public $def_lang;
    public $curURI;
    public $lang_panel;
    public $template;
    public $work_controller;
    public $work_action;
    public $pathIDs = array();
    public $pathMenuIDs = array();
    public $doc_id = 0;
    public $catalog_id = 0;
    public $befor_path = array();
    public $after_path = array();
    protected $_redirector = null;

    public $style = array(0 => 'red'
        , 1 => 'blue'
        , 2 => 'green'
        , 3 => 'grey'
        , 4 => 'yellow'
        , 5 => 'fiolet'
        , 6 => 'yellow2'
    );

    function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        parent::init();

        $this->_redirector = $this->_helper->getHelper('Redirector');

        $this->work_controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->work_action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

        Zend_Loader::loadClass('AnotherPages');
        Zend_Loader::loadClass('Catalogue');
        Zend_Loader::loadClass('SystemSets');
        Zend_Loader::loadClass('SectionAlign');
        Zend_Loader::loadClass('Vopros');
        Zend_Loader::loadClass('Textes');
        Zend_Loader::loadClass('FileTypes');
        Zend_Loader::loadClass('Gallery');
        Zend_Loader::loadClass('News');
        Zend_Loader::loadClass('Article');

        $http = new Zend_Controller_Request_Http();
        $this->domXml = &$this->view->serializer;

        $this->cmf = Zend_Controller_Front::getInstance()->getParam('cmf');

        if ($http->getCookie('sklad_vote'))
            $this->site_vote = $http->getCookie('sklad_vote');

        $this->curURI = $http->getRequestUri();
        $this->curURI = str_replace("/index/index", "", $this->curURI);
        $this->curURI = str_replace('&', '&amp;', $this->curURI);

        preg_match("/\/print\/([^\/]*)/", $this->curURI, $m1);
        if (!empty($m1))
            $this->_setParam('print', $m1[1]);
        $this->print = $this->_getParam('print');

        $this->domXml->create_element('page', "", 1);
        if ($this->print == 'yes')
            $this->domXml->set_attribute(array('print' => $this->print));

        $this->domXml->create_element('currentURL', $this->curURI, 1);
        $this->domXml->set_tag('//page', true);

        $this->AnotherPages = new AnotherPages();
        $this->Catalogue = new Catalogue();
        $this->SystemSets = new SystemSets();
        $this->SectionAlign = new SectionAlign();
        $this->Vopros = new Vopros();
        $this->Textes = new Textes();
        $this->FileTypes = new FileTypes();
        $this->Gallery = new Gallery();
        $this->News = new News();
        $this->Article = new Article();

        //Язык системы по умолчанию
        $this->def_lang = $this->getDefaultLang();
        if ($this->def_lang == '') {
            $this->def_lang = 'ru';
        }

        //Текущий язык при открытии страницы
        if (preg_match("/\/lng\/([^\/]*)/", $this->curURI, $matches)) {
            $curr_lng = $matches[1];
        } else {
            $curr_lng = 'ru';
        }

        if (!empty($curr_lng)) {
            $this->lang = $curr_lng;
            setcookie("site_lang", "", time() - 3600);
            setcookie("site_lang", $this->lang, time() + 3600 * 24 * 3, "/");
        } else {
            //Определяем, первый это заход или нет
            if ($http->getCookie('site_first')) {
                $first = $http->getCookie('site_first');
            } else {
                $first = '';
            }

            if ($first == '') {
                //Первый заход
                if ($http->getCookie('site_lang')) {
                    $this->lang = $http->getCookie('site_lang');
                } else {
                    $this->lang = '';
                }

                setcookie("site_lang", "", time() - 3600);
                setcookie("site_lang", $this->lang, time() + 3600 * 24 * 3, "/");
                setcookie("site_first", "1", 0, "/");
            }
            else {
                if ($http->getCookie('site_lang')) {
                    $this->lang = $http->getCookie('site_lang');
                } else {
                    $this->lang = '';
                }

                setcookie("site_lang", "", time() - 3600);
                setcookie("site_lang", $this->lang, time() + 3600 * 24 * 3, "/");
            }
        }

        $this->lang_id = $this->getLanguageId($this->lang);
        $printText = $this->getSysText('print_text');
        if ($this->print) {
            $this->getSysText('print_top_right_text');
            $this->domXml->create_element('referer', $_SERVER['SERVER_NAME'], 1);
        }

        $this->domXml->set_tag('//page', true);
        $this->getLanguageInfo();

        $this->domXml->set_tag('//page', true);
        $this->getOurBanner();
        $this->getFileTypes();
        $this->getLeftBanners();

        if ($this->work_controller == 'cat' && $this->work_action != 'all') {
            $this->initPathIDs();
        } else {
            $this->initMenuPathIDs();
        }

        $this->getCatTree();

        $this->makeMenu(1);
        $this->getLangs();
        $this->getVopros();

        $this->refererPhones();

        $this->getSysText('search_text');
        $this->getSysText('image_map');
        $this->getSysText('text_zakaz_callback');
        $this->getSysText('text_callback_callback');
        $this->getSysText('text_callback_sendyournumber');
        $this->getSysText('text_callback_name');
        $this->getSysText('text_callback_phone');
        $this->getSysText('text_callback_timeforcall');
        $this->getSysText('text_callback_message');
        $this->getSysText('text_callback_send');
        $this->getSysText('text_callback_from');
        $this->getSysText('text_callback_till');
        $this->getSysText('text_callback_ticket');
        $this->getSysText('text_complain_ticket');
        $this->getSysText('text_zakaz_phone');

        $this->getSysText('text_complain_name');
        $this->getSysText('text_complain_phone');
        $this->getSysText('text_complain_email');
        $this->getSysText('text_complain_message');
        $this->getSysText('text_complain_send');

        $this->getSysText('item_catalog');

        $this->getBanners('banner_image_map', 1, 1);

        $this->getBanners('banner_header_slogan', 2, 2);
        $this->getBanners('banner_header_phone1', 2, 3);
        $this->getBanners('banner_header_phone2', 2, 4);
        $this->getBanners('banner_header_address', 2, 5);

        $this->getBanners('banner_footer_address', 3, 6);
        $this->getBanners('banner_footer_copy', 3, 7);
        $this->getBanners('banner_footer_slogan', 3, 8);

        $this->getBanners('banner_java_scripts', 8, 13);

        $this->getBanners('banner_left_side_menu', 9, 14);
        $this->getBanners('banner_left_side', 10, 15);

        $this->getSokobamLevels();
    }

    public function postDispatch()
    {
        if ($this->print)
            $template = 'print.xsl';
        else if ($this->template)
            $template = $this->template;
        else
            $template = $this->work_controller . '_' . $this->work_action . '.xsl';

        $path_ = $this->view->getScriptPath($template);
        if (!is_file($path_)) {
            $path_ = $this->work_controller . '.xsl';
        } else {
            $path_ = $template;
        }

        $this->view->render($path_);
    }

    public function getLanguageInfo()
    {
        if ($this->lang != 'ru') {
            $lang_name = "/" . $this->lang;
            $lang_sys_name = "/" . $this->lang;
        } else {
            $lang_name = "";
            $lang_sys_name = "";
        }

        if (strchr($this->curURI, "/lng"))
            $clearURI = substr($this->curURI, 0, strpos($this->curURI, "/lng"));
        else
            $clearURI = substr($this->curURI, 1);

        if ($clearURI == "/index/" || $clearURI == "//")
            $clearURI = "/";

        $this->domXml->create_element('lang', $this->lang, 1);
        $this->domXml->create_element('lang_id', $this->lang_id, 1);
        $this->domXml->create_element('lang_name', $lang_name, 1);
        $this->domXml->create_element('lang_sys_name', $lang_sys_name, 1);
        $this->domXml->create_element('clearURI', $clearURI, 1);
    }

    /**
     * Метод для инициализации основного XML шаблона страницы
     * @access   public
     * @param    array $open_data
     * @return   string xml
     */
    public function openData($open_data)
    {
        $this->domXml->create_element('data', '', 1);
        $this->domXml->set_attribute($open_data);
    }

    function getOurBanner()
    {
        $a = '';
//      $a = S_bann(358);
//      $a = str_replace('&','&amp;',$a);
//      if($_SERVER["REMOTE_ADDR"]=="91.197.128.114"){
//        var_dump($a);
//        exit;
//      }
//      $this->setXmlNode($a, 'get_our_banner');
        $this->domXml->create_element('get_our_banner', '', 2);
        $this->domXml->import_node($a, true);
        $this->domXml->go_to_parent();
    }

    /**
     * Метод для получения информации о картинке
     * @access   public
     * @param    string $stringImage
     * @param    string $delimiter
     * @return   array  $Out
     */
    function splitImageProperties($stringImage, $delimiter = "#")
    {
        if (!$stringImage)
            return null;
        $tmp = explode($delimiter, $stringImage);
        $Out[0]['src'] = $tmp[0];
        $Out[0]['w'] = $tmp[1];
        $Out[0]['h'] = $tmp[2];
        return $Out;
    }

    /**
     * Метод для получения ИД страницы(для doc страниц)
     * @access   public
     * @param    integer $id
     * @return   string xml
     */
//    public function getDocId($id){
//       return $this->AnotherPages->getDocId($id);
//    }

    /**
     * Метод для получения ИД страницы(для всех остальных страниц)
     * @access   public
     * @param    integer $id
     * @return   string xml
     */
    public function getPageId($id)
    {
        return $this->AnotherPages->getPageId($id);
    }

    /**
     * Метод для получения информации о странице
     * @access   public
     * @param    integer $id
     * @return   string xml
     */
    public function getDocInfo($id)
    {
        $info = $this->AnotherPages->getDocInfo($id, $this->lang_id);
        if ($info) {
            $this->domXml->set_tag('//data', true);

            $this->domXml->create_element('sectioninfo', '', 2);

            $image = $this->AnotherPages->getSectionImage($id);

            if (!empty($image) && strchr($image, "#")) {
                $tmp = explode('#', $image);
                $this->domXml->create_element('image', '', 2);
                $this->domXml->set_attribute(array('src' => '/images/pg/'.$tmp[0]
                    , 'w' => $tmp[1]
                    , 'h' => $tmp[2]
                ));
                $this->domXml->go_to_parent();
            }

            $this->domXml->go_to_parent();
        }
    }

    public function getDocXml($id = 0, $type = 0, $tag = false, $lang = 0)
    {
        $doc = $this->AnotherPages->getDocXml($id, $type, $lang);
        $doc = stripslashes($doc);


        if (!empty($doc)) {
            $pattern = '/<a(.+)href="([http:\/\/|https:\/\/]?.+)"/Uis';
            $doc = preg_replace_callback($pattern, 'addSizeText', $doc);

            $pattern = '/(<a.*href=".*\.(\w+)) size=(.+)"/Uis';
            $doc = preg_replace($pattern, '${1}" typeDoc="$2" size="$3"', $doc);

            if ($tag) {
                $txt = "<?xml version=\"1.0\" encoding=\"{$this->domXml->get_encoding()}\"?><!DOCTYPE stylesheet SYSTEM \"symbols.ent\"><txt>" . $doc . "</txt>";
            } else {
                $txt = "<?xml version=\"1.0\" encoding=\"{$this->domXml->get_encoding()}\"?><!DOCTYPE stylesheet SYSTEM \"symbols.ent\">" . $doc;
            }
            $this->domXml->import_node($txt, false);
        }
    }

    public function setXmlNode($doc, $tag = '')
    {
        if (!empty($doc)) {
            $pattern = '/<a(.+)href="([http:\/\/|https:\/\/]?.+)"/Uis';
            $doc = preg_replace_callback($pattern, 'addSizeText', $doc);

            $pattern = '/(<a.*href=".*\.(\w+)) size=(.+)"/Uis';
            $doc = preg_replace($pattern, '${1}" typeDoc="$2" size="$3"', $doc);

            if ($tag) {
                $txt = "<?xml version=\"1.0\" encoding=\"{$this->domXml->get_encoding()}\"?><!DOCTYPE stylesheet SYSTEM \"symbols.ent\"><$tag>" . $doc . "</$tag>";
            } else {
                $txt = "<?xml version=\"1.0\" encoding=\"{$this->domXml->get_encoding()}\"?><!DOCTYPE stylesheet SYSTEM \"symbols.ent\">" . $doc;
            }
            $this->domXml->import_node($txt, false);
        }
    }

    public function getFileTypes()
    {
        $types = $this->FileTypes->getFileTypes();
        if (!empty($types)) {
            foreach ($types as $view) {
                $this->domXml->create_element('file_types', '', 2);
                $this->domXml->set_attribute(array('type' => $view['EXT']
                ));

                $href = '';
                if (!empty($view['IMAGE1'])) {
                    $_temp = explode('#', $view['IMAGE1']);
                    $href = '/images/filetypes/' . $_temp[0];
                }

                $this->domXml->create_element('url', $href);

                $this->domXml->go_to_parent();
            }
        }
    }

    public function convertSize($size)
    {
        $unit = array('b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb', 'Eb', 'Zb', 'Yb');
        return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . $unit[$i];
    }

    public function getSysText($indent)
    {
        $textes = $this->Textes->getSysText($indent, $this->lang_id);

        if (!empty($textes['DESCRIPTION']) || !empty($textes['IMAGE'])) {
            $description = $textes['DESCRIPTION'];
            $this->domXml->set_tag('//page', true);
            $this->domXml->create_element($indent, $description, 2);
            if (!empty($textes['IMAGE']) && strchr($textes['IMAGE'], "#")) {
                $tmp = explode('#', $textes['IMAGE']);
                $this->domXml->set_attribute(array('src' => $tmp[0],
                    'w' => $tmp[1],
                    'h' => $tmp[2]
                ));
            }
            $this->domXml->go_to_parent();
        }
    }

    /**
     * Метод для получения XML постраничной навигации для новостей
     * @access   public
     * @param    integer $group
     * @param    integer $page
     * @param    integer $pageSize
     * @return   string xml
     */
    public function makeSectionInfo($group, $page, $pageSize)
    {
        $sectionInfo = $this->News->makeSectionInfo($group, $page, $pageSize);
        $this->domXml->set_tag('//page/data', true);
        $this->domXml->create_element('section', '', 2);
        $this->domXml->set_attribute(array('count' => $sectionInfo['count']
            , 'page' => $sectionInfo['page']
            , 'pcount' => $sectionInfo['pcount']
        ));
    }

    private function decoreUrl($uri)
    {
        $uri = str_replace('test/', '', $uri);
        $decor = new BaseClear($uri);
        $cleaner = new ClearPrint($decor);
        $cleaner = new ClearLng($cleaner);
        $cleaner = new ClearPage($cleaner);
        $cleaner = new ClearCount($cleaner);
        $cleaner = new ClearPcount($cleaner);
        $cleaner = new ClearSearch($cleaner);
        $cleaner = new ClearGet($cleaner);
        $cleaner->clear($decor->uri);

        return $decor;
    }

    private function getDocId()
    {
        $doc_id = 0;
        if (!is_numeric($this->work_action)) {
            $uri = $this->curURI;

            if ($this->lang != $this->def_lang)
                $uri = '/lng' . $uri;

            $decor = $this->decoreUrl($uri);
            $uri = $decor->uri;

            $pattern_page = '/(.*)page\/(\d*)\//is';
            preg_match($pattern_page, $uri, $out);
            if (!empty($out[1])) {
                $uri = $out[1];
            }

            $doc_id = $this->AnotherPages->getPageByURL($uri . '/');

            if ($doc_id === false) {
                $doc_id = $this->AnotherPages->getDocId($uri);
            }

            if ($doc_id === false) {
                $doc_id = $this->AnotherPages->getPageId($uri);
            }

            unset($decor);
            unset($cleaner);
        } elseif (($this->work_controller == 'doc') && is_numeric($this->work_action)) {
            $doc_id = $this->work_action;
        }

        return $doc_id;
    }

    private function initMenuPathIDs()
    {
        $this->doc_id = $this->getDocId();
        if (isset($this->doc_id) && !empty($this->doc_id)) {
            $this->pathMenuIDs = $this->AnotherPages->getParents($this->doc_id);
            $this->pathMenuIDs[count($this->pathMenuIDs)] = $this->doc_id;
        }
    }

    /**
     * Метод вывода меню сайта
     */
    public function makeMenu($parentID = 0, $level = 1)
    {
        $pathIDs = array();
        $menu = $this->AnotherPages->getTree($parentID, $this->lang_id);
        if (!empty($menu)) {
            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
            else
                $lang = '';

            foreach ($menu as $view) {
                if (is_array($this->pathMenuIDs) && !empty($this->pathMenuIDs)) {
                    $on_path = (in_array($view['ANOTHER_PAGES_ID'], $this->pathMenuIDs) ? 1 : 0);
                } else {
                    $on_path = 0;
                }

                $this->domXml->create_element('main_menu', '', 2);
                $this->domXml->set_attribute(array('another_pages_id' => $view['ANOTHER_PAGES_ID']
                    , 'parent_id' => $view['PARENT_ID']
                    , 'is_new_win' => $view['IS_NEW_WIN']
                    , 'is_node' => $view['IS_NODE']
                    , 'via_js' => $view['VIA_JS']
                    , 'on_path' => $on_path
                    , 'level' => $level
                ));

                $href = '';
                $is_lang = false;
                if (!empty($view['URL']) && strpos($view['URL'], 'http://') !== false) {
                    $is_lang = true;
                    $href = $view['URL'];
                } elseif (!empty($view['URL'])) {
                    $href = $view['URL'];
                } elseif (!empty($view['REALCATNAME']) && $view['REALCATNAME'] != '/') {
                    $is_lang = true;
//            $href = '/doc'.$view['REALCATNAME'];
                    $href = $view['REALCATNAME'];
                } else {
                    $is_lang = true;
                    $href = '/doc/' . $view['ANOTHER_PAGES_ID'] . '/';
                }

                $_href = $this->AnotherPages->getSefURLbyOldURL($href);
                if (!empty($_href) && $is_lang)
                    $href = $lang . $_href;
                elseif (!empty($_href) && !$is_lang)
                    $href = $_href;
                /* Заменяем обычные пробелы на неразрывные вида &#160; */
                //   $view['NAME'] = $this->removeNBSP($view['NAME']);
                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('url', $href);
                $this->domXml->create_element('spec_url', $view['URL']);

                if ($view['URL'] == '/cat/all/') {
                    $this->getCatTree(0, 'main_menu');
                } else {
                    $level++;
                    $this->makeMenu($view['ANOTHER_PAGES_ID'], $level);
                    $level--;
                }

                $this->domXml->go_to_parent();
            }
        }
    }

    private function removeNBSP($value)
    {
        $value = str_replace(" ", "&#160;", $value);
        return $value;
    }

    private function getCatalogId()
    {
        $catalogue_id = 0;
        if ($this->work_action == 'all' || empty($this->work_action))
            $catalogue_id = 0;
        else {
            if (!is_numeric($this->work_action)) {
                $uri = $this->curURI;

                preg_match("/\/cat\/([^\/]*)/", $this->curURI, $m);
                $work = '';
                if (!empty($m)) {
                    $work = $m[1];
                }


//            $work = str_replace('-','/', $work);
                if (!empty($work)) {
                    $catalogue_id = $this->Catalogue->getCatalogueId($work);  //exit;
                    if (empty($catalogue_id))
                        $catalogue_id = $this->Catalogue->getCatalogueIdByUrl($this->curURI);
                    preg_match("/\/count\/([^\/]*)/", $this->curURI, $m1);
                    if (!empty($m1))
                        $this->_setParam('count', $m1[1]);

                    preg_match("/\/pcount\/([^\/]*)/", $this->curURI, $m2);
                    if (!empty($m2))
                        $this->_setParam('pcount', $m2[1]);
                }
            }
            else {
                $catalogue_id = $this->work_action;
            }
        }

        return $catalogue_id;
    }

    private function initPathIDs()
    {
        $n = $this->_getParam('n');
        if ($this->work_controller == 'cat' && $this->work_action != 'item') {
            $this->catalog_id = !empty($n) ? $n : $this->getCatalogId();
            if (!empty($this->catalog_id)) {
                $this->pathIDs = $this->Catalogue->getAllParents($this->catalog_id,
                                                                 $this->pathIDs);
                $this->pathIDs[count($this->pathIDs)] = $this->catalog_id;
            }
        } elseif ($this->work_controller == 'cat' && $this->work_action == 'item') {
            $this->catalog_id = $n;
            if (!empty($this->catalog_id)) {
                $this->pathIDs = $this->Catalogue->getAllParents($this->catalog_id,
                                                                 $this->pathIDs);
                $this->pathIDs[count($this->pathIDs)] = $this->catalog_id;
            }
        }
    }

    public function getCatTree($parentId = 0, $section = 'cattree')
    {
        $cats = $this->Catalogue->getTree($parentId, $this->lang_id);

        if (!empty($cats)) {
            if ($this->lang_id > 0) {
                $lang = '/' . $this->lang;
            } else {
                $lang = '';
            }

            Zend_Loader::loadClass('CatHelper');

            foreach ($cats as $cat) {
                $on_path = 0;
                if (is_array($this->pathIDs) && !empty($this->pathIDs)) {
                    $on_path = (in_array($cat['CATALOGUE_ID'], $this->pathIDs) ? 1 : 0);
                }
                

                $children_item_count = $this->Catalogue->getItemsCount($cat['CATALOGUE_ID']);

                $this->domXml->create_element($section, '', 2);
                $this->domXml->set_attribute(array('catalogue_id' => $cat['CATALOGUE_ID']
                    , 'parent_id' => $cat['PARENT_ID']
                    , 'in_main' => $cat['STATUS_MAIN']
                    , 'in_menu' => $cat['IN_MENU']
                    , 'on_path' => $on_path
                    , 'item_count' => $children_item_count
                        )
                );

                $is_lang = false;
                $href = '';
                if (!empty($cat['URL']) && strpos($cat['URL'], 'http://') !== false) {
                    $is_lang = true;
                    $href = $cat['URL'];
                } elseif (!empty($cat['URL'])) {
                    $href = $cat['URL'];
//                } elseif (!empty($cat['REALCATNAME']) && $cat['REALCATNAME'] != '/') {
//                    $href = '/cat' . $cat['REALCATNAME'];
//                    $is_lang = true;
                } else {
//                    $href = '/cat/' . $cat['CATALOGUE_ID'] . '/';
                    $href = '/cat/view/n/' . $cat['CATALOGUE_ID'] . '/';
                    $is_lang = true;
                }

                $_href = $this->AnotherPages->getSefURLbyOldURL($href);

                if (!empty($_href) && $is_lang)
                    $href = $lang . $_href;
                elseif (!empty($_href) && !$is_lang)
                    $href = $_href;

                $this->domXml->create_element('name', $cat['NAME']);
                $this->domXml->create_element('catname', $cat['CATNAME']);
                $this->domXml->create_element('realcatname', $cat['REALCATNAME']);
                $this->domXml->create_element('style', $this->style[$cat['COLOR_STYLE']]);
                $this->domXml->create_element('url', $href, 3, array(), 1);
//          $this->domXml->create_element('url',$href);

                if (!empty($cat['IMAGE1']) && strchr($cat['IMAGE1'], "#")) {
                    $tmp = explode('#', $cat['IMAGE1']);
                    $this->domXml->create_element('image1', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0],
                        'w' => $tmp[1],
                        'h' => $tmp[2]
                            )
                    );
                    $this->domXml->go_to_parent();
                }

                if (!empty($cat['IMAGE2']) && strchr($cat['IMAGE2'], "#")) {
                    $tmp = explode('#', $cat['IMAGE2']);
                    $this->domXml->create_element('image2', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0],
                        'w' => $tmp[1],
                        'h' => $tmp[2]
                            )
                    );
                    $this->domXml->go_to_parent();
                }

                if ($section == 'cattree') {
                    $this->getCatTree($cat['CATALOGUE_ID'], $section);
                }

                if ($children_item_count > 0) {
                    $params['lang_id'] = $this->lang_id;
                    $params['lang'] = $this->lang;

                    $catHelper = new CatHelper($params);
                    $domXml = $catHelper->getItems($cat['CATALOGUE_ID'], $this->_getParam('it'));

                    $this->domXml->appendXML($domXml->getXMLobject());
                }

                $this->domXml->go_to_parent();
            }
        }
    }

    /**
     * Метод вывода страницы 404
     */
    public function page_404()
    {
        $this->getResponse()->setHttpResponseCode(404);
        $this->getResponse()->sendHeaders();
        echo "<h1>404 Not found!</h1>";
//      $this->getCatTree();
//      $this->cmf->Transform('404.xsl',$this->domXml->getXML());
        exit;
    }

    public function initParents($catalogue_id)
    {
        $parents = array();
        $parents[] = $catalogue_id;

        while ($catalogue_id > 0) {
            $cat = $this->Catalogue->getParents($catalogue_id);
            $parents[] = $cat['CATALOGUE_ID'];
            $catalogue_id = $cat['PARENT_ID'];
        }

        return $parents;
    }

    /**
     * Метод для получения XML для баннерных мест
     *
     * @param string $block
     * @param int    $align
     * @param int    $section
     * @param int    $preg
     */
    public function getBanners($block, $align, $section, $preg = 1)
    {
        $banners = $this->SectionAlign->getBanners($align, $section, $this->lang_id);

        if ($banners) {
            foreach ($banners as $banner) {
                $this->domXml->create_element($block, '', 1);
                $this->domXml->create_element('section_align_id',
                                              $banner['SECTION_ALIGN_ID']);

                $this->domXml->create_element('alt', $banner['ALT']);
//             $this->domXml->create_element('description',$banner['DESCRIPTION']);

                if ($preg == 1) {
                    $pattern = '/<p>(.*)<\/p>/Uis';
                    preg_match_all($pattern, $banner['DESCRIPTION'], $out);
                    $banner['DESCRIPTION'] = (isset($out[1][0]) && !empty($out[1][0])) ? $out[1][0] : $banner['DESCRIPTION'];
                }

                $this->setXmlNode($banner['DESCRIPTION'], 'description');

                if (!empty($banner['BANNER_CODE'])) {
                    $this->setXmlNode($banner['BANNER_CODE'], 'banner_code');
                }

                $this->domXml->create_element('type', $banner['TYPE']);
                $this->domXml->create_element('url', $banner['URL']);
                $this->domXml->create_element('newwin', $banner['NEWWIN']);
                $this->domXml->create_element('burl', $this->bannerURL($banner['URL']));

                if ($banner['IMAGE1'] != '' && strchr($banner['IMAGE1'], "#")) {
                    $image = $this->splitImageProperties($banner['IMAGE1']);
                    $this->domXml->create_element('image', '', 2);

                    $this->domXml->set_attribute(array('src' => $image[0]['src'],
                        'w' => $image[0]['w'],
                        'h' => $image[0]['h']
                            )
                    );
                    $this->domXml->go_to_parent();
                }

                $this->domXml->go_to_parent();
            }
        }
    }

    /**
     * Метод для получения XML для баннерных мест
     * @access   public
     * @param    integer $lang
     * @param    string $currency
     * @return   string xml
     */
    public function getBannerFromVar($block, $banner)
    {
        if ($banner) {
            $this->domXml->create_element($block, '', 1);
            $this->domXml->create_element('section_align_id',
                                          $banner['SECTION_ALIGN_ID']);

            $this->domXml->create_element('alt', $banner['ALT']);

            $pattern = '/<p>(.*)<\/p>/Uis';
            preg_match_all($pattern, $banner['DESCRIPTION'], $out);
            $banner['DESCRIPTION'] = (isset($out[1][0]) && !empty($out[1][0])) ? $out[1][0] : $banner['DESCRIPTION'];

            $this->setXmlNode($banner['DESCRIPTION'], 'description');

            $this->domXml->create_element('type', $banner['TYPE']);
            $this->domXml->create_element('url', $banner['URL']);
            $this->domXml->create_element('newwin', $banner['NEWWIN']);
            $this->domXml->create_element('burl',
                                          $this->bannerURL($banner['URL']));
            if ($banner['IMAGE1'] != '' && strchr($banner['IMAGE1'], "#")) {
                $image = $this->splitImageProperties($banner['IMAGE1']);
                $this->domXml->create_element('image', '', 2);

                $this->domXml->set_attribute(array('src' => $image[0]['src'],
                    'w' => $image[0]['w'],
                    'h' => $image[0]['h']
                        )
                );
                $this->domXml->go_to_parent();
            }

            $this->domXml->go_to_parent();
        }
    }

    private function bannerURL($url)
    {
        $burl = '';

        if (!empty($url) || strchr($url, "http:")) {
            $burl = $url;
        } else {
            if (!empty($url)) {
                if (strchr($url, "doc")) {
                    if (substr($url, 0, 1) != "/") {
                        $burl .= "/";
                    }
                    $burl .= $url;
                }
                else {
                    if (substr($url, 0, 1) != "/") {
                        $burl = "/doc/" . $url;
                    } else {
                        $burl = "/doc" . $url;
                    }
                }
                if (substr($url, -1) != "/") {
                    $burl .="/";
                }
            } else {
                $burl = '';
            }
        }

        if (!empty($burl)) {
            $url = $burl;
        } else {
            $url = '';
        }

        return $url;
    }

    protected  function getRootPath()
    {
        if ($this->lang_id > 0) {
            $lang = '/' . $this->lang;
        } else {
            $lang = '';
        }

        $this->domXml->create_element('breadcrumbs', '', 2);
        $this->domXml->set_attribute(array('id' => 9
            , 'parent_id' => 0
        ));

        $href = $lang . '/';

        $textes = $this->Textes->getSysText('page_main', $this->lang_id);

        $this->domXml->create_element('name', $textes['DESCRIPTION']);
        $this->domXml->create_element('url', $href);
        $this->domXml->go_to_parent();
    }

    protected function getBeforPath()
    {
        if ($this->lang_id > 0)
            $lang = '/' . $this->lang;
        else
            $lang = '';

        foreach ($this->befor_path as $view) {
            $this->domXml->create_element('breadcrumbs', '', 2);
            $this->domXml->set_attribute(array('id' => 0
                , 'parent_id' => 0
            ));

            $href = $view['url'];

            $this->domXml->create_element('name', $view['name']);
            $this->domXml->create_element('url', $href);
            $this->domXml->go_to_parent();
        }
    }

    protected function getAfterPath()
    {
        if ($this->lang_id > 0)
            $lang = '/' . $this->lang;
        else
            $lang = '';

        foreach ($this->after_path as $view) {
            $this->domXml->create_element('breadcrumbs', '', 2);
            $this->domXml->set_attribute(array('id' => 0
                , 'parent_id' => 0
            ));

            $href = '/' . $view['url'];

            $this->domXml->create_element('name', $view['name']);
            $this->domXml->create_element('url', $href);
            $this->domXml->go_to_parent();
        }
    }

    public function getPath($id)
    {
//       $parent = $this->Catalogue->getParents($id);
        $childs = array();
        $childs[count($childs)] = $id;
        $parent = $id;

        while ($parent > 0) {
            $cat = $this->Catalogue->getParents($parent, $this->lang_id);
            $parent = $cat['PARENT_ID'];
            if ($parent == 0)
                break;
            $childs[count($childs)] = $cat['PARENT_ID'];
        }

        $this->getRootPath();
        $this->getBeforPath();

        if (!empty($childs)) {
            $childs = array_reverse($childs);
            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
            else
                $lang = '';

            foreach ($childs as $view) {
                $parent = $this->Catalogue->getParents($view, $this->lang_id);
                if (!empty($parent)) {
                    $this->domXml->create_element('breadcrumbs', '', 2);
                    $this->domXml->set_attribute(array('id' => $parent['CATALOGUE_ID'],
                        'parent_id' => $parent['PARENT_ID']
                            )
                    );

                    $children_item_count = $this->Catalogue->getItemsCount($parent['CATALOGUE_ID']);

                    $href = '';
                    $is_lang = false;
                    if ($children_item_count > 0) {
                        $is_lang = true;
                        $href = '/cat/view/n/' . $parent['CATALOGUE_ID'] . '/';
                    } else {
                        if (!empty($parent['URL'])) {
                            $is_lang = true;
                            $href = $parent['URL'];
                        } elseif (!empty($parent['REALCATNAME']) && $parent['REALCATNAME'] != '/') {
                            $is_lang = true;
                            $href = '/cat' . $parent['REALCATNAME'];
                        } else {
                            $is_lang = true;
                            $href = '/cat/' . $parent['CATALOGUE_ID'] . '/';
                        }
                    }

                    $_href = $this->AnotherPages->getSefURLbyOldURL($href);
                    if (!empty($_href) && $is_lang)
                        $href = $lang . $_href;
                    elseif (!empty($_href) && !$is_lang)
                        $href = $_href;

                    $this->domXml->create_element('name', trim($parent['NAME']));
                    $this->domXml->create_element('url', $href);
                    $this->domXml->go_to_parent();
                }
            }
        }

        $this->getAfterPath();
    }

    public function getDocPath($id)
    {
        $parent = $this->AnotherPages->getPath($id);
        if (!empty($parent)) {
            if ($this->lang_id > 0) {
                $lang = '/' . $this->lang;
            } else {
                $lang = '';
            }

            $this->getRootPath();
            $this->getBeforPath();

            foreach ($parent as $view) {
//                if ($view['PARENT_ID'] > 0 && $view['IS_NODE'] == 0) {
                if ($view['PARENT_ID'] > 0) {
                    $this->domXml->create_element('breadcrumbs', '', 2);
                    $this->domXml->set_attribute(array('id' => $view['ANOTHER_PAGES_ID']
                        , 'parent_id' => $view['PARENT_ID']
                    ));

                    $is_lang = false;
                    if (!empty($view['URL']) && strpos($view['URL'], 'http://') !== false) {
                        $is_lang = true;
                        $href = $view['URL'];
                    } elseif (!empty($view['URL'])) {
                        $href = $view['URL'];
                    } elseif (!empty($view['REALCATNAME']) && $view['REALCATNAME'] != '/') {
                        $is_lang = true;
                        //           $href = '/doc'.$view['REALCATNAME'];
                        $href = $view['REALCATNAME'];
                    } else {
                        $is_lang = true;
                        $href = '/doc/' . $view['ANOTHER_PAGES_ID'] . '/';
                    }

                    $_href = $this->AnotherPages->getSefURLbyOldURL($href);
                    if (!empty($_href) && $is_lang) {
                        $href = $lang . $_href;
                    } elseif (!empty($_href) && !$is_lang) {
                        $href = $_href;
                    }

                    $this->domXml->create_element('name', $view['NAME']);
                    $this->domXml->create_element('url', $href);
                    $this->domXml->go_to_parent();
                }
            }

            $this->getAfterPath();
        }
    }

    public function getGlobalPath()
    {
        $this->getRootPath();
        $this->getBeforPath();
        $this->getAfterPath();
    }

    public function getSettingValue($name)
    {
        return $this->SystemSets->getSettingValue($name);
    }

    public function form_processing($data)
    {
        if (!empty($data) && is_array($data)) {
            foreach ($data as $k => $view) {
                $view = trim($view);
                $tmp_ = $k . '_strip';
                if (isset($data[$tmp_]) && $data[$tmp_] === false) {

                } else {
                    $view = strip_tags($view);
                }

                $data[$k] = addslashes($view);
            }
        }

        return $data;
    }

    public function viewErrors($err)
    {
        if (!empty($err) && is_array($err)) {
            foreach ($err as $k => $view) {
                $this->domXml->create_element('error_messages', '', 2);
                $this->domXml->create_element('err_mess', $view);
                $this->domXml->go_to_parent();
            }
        }
    }

    public function sendErrorData($data)
    {
        if (!empty($data)) {
            foreach ($data as $k => $view) {
                $tag_name = strtolower($k) . "_err";
                $this->domXml->create_element($tag_name, $view, 2);
                $this->domXml->go_to_parent();
            }
        }
    }

    public function sessionExist()
    {
        if (isset($_SESSION['ses_user_id']) && !empty($_SESSION['ses_user_id']))
            return true;
        else
            return false;
    }

    public function getVopros()
    {
        $this->domXml->create_element('site_vote', $this->site_vote, 2);
        $this->domXml->go_to_parent();

        $vopros = $this->Vopros->getVopros($this->lang_id);

        if (!empty($vopros)) {
            $this->domXml->set_tag('//page', true);
            $this->domXml->create_element('vopros', '', 2);
            $this->domXml->set_attribute(array('id' => $vopros['VOPROS_ID']
                , 'count' => $vopros['COUNT_']
            ));

            $this->domXml->create_element('name', $vopros['NAME']);

            $otvets = $this->Vopros->getOtvets($vopros['VOPROS_ID'],
                                               $this->lang_id);
            if (!empty($otvets)) {
                foreach ($otvets as $view) {
                    $this->domXml->create_element('otvets', '', 2);
                    if ($vopros['COUNT_'] > 0)
                        $percent = round(($view['COUNT_'] * 100) / $vopros['COUNT_'],
                                         2);
                    else
                        $percent = 0;

                    $this->domXml->set_attribute(array('id' => $view['OTVETS_ID']
                        , 'percent' => $percent
                    ));

                    $this->domXml->create_element('name', $view['NAME']);

                    $this->domXml->go_to_parent();
                }
            }

            $this->domXml->go_to_parent();
        }
    }

    /**
     * Метод получающий основной язык системы
     * @access   public
     * @param
     * @param
     * @return  string xml
     */
    public function getDefaultLang()
    {
        return $this->AnotherPages->getDefaultLang();
    }

    /**
     * Метод для формирования списка языков системы
     * @access   public
     * @param
     * @param
     * @return  string xml
     */
    public function getLangs()
    {
        $langs = $this->AnotherPages->getLangs();
        foreach ($langs as $lang) {
            $this->domXml->create_element('langs', '', 1);
            $this->domXml->set_attribute(array('cmf_lang_id' => $lang['CMF_LANG_ID']
                , 'is_default' => $lang['IS_DEFAULT']
            ));

            $uri = explode('/', $this->curURI);
            $href = '';
            if ($this->lang_id > 0) {
                for ($i = 0; $i < count($uri) - 2; $i++) {
                    if (!empty($uri[$i]))
                        $href.= $uri[$i] . '/';
                }
            }
            else {
                for ($i = 0; $i < count($uri); $i++) {
                    if (!empty($uri[$i]))
                        $href.= $uri[$i] . '/';
                }
            }

            if ($lang['IS_DEFAULT'] == 0) {
                $href = '/' . $lang['SYSTEM_NAME'] . '/' . $href;
            } else {
                $href = '/' . $href;
            }

            $this->domXml->create_element('name', $lang['NAME']);
            $this->domXml->create_element('system_name', $lang['SYSTEM_NAME']);
            $this->domXml->create_element('url', $href);

            $this->domXml->go_to_parent();
        }
    }

    /**
     * Метод для получения ИД выбранного языка
     * @access   public
     * @param    string $lang
     * @return   integer
     */
    public function getLanguageId($lang)
    {
        return $this->AnotherPages->getLanguageId($lang);
    }

    /**
     * отправка писем
     *
     * @param $to      == куда
     * @param $message == текст сообщения
     * @param $subject == тема
     * @return результат отправки
     */
    public function sendMail($to, $message, $subject, $attach = '', $name = '', $attach_type = ''){
        Zend_Loader::loadClass('Zend_Mail');

        $email_from = $this->getSettingValue('email_from');
        $patrern = '/(.*)<?([a-zA-Z0-9\-\_]+\@[a-zA-Z0-9\-\_]+(\.[a-zA-Z0-9]+?)+?)>?/U';
        preg_match_all($patrern, $email_from, $arr);

        $mailerFrom = empty($arr[2][0]) ? '' : trim($arr[2][0]);
        $mailerFromName = empty($arr[1][0]) ? '' : trim($arr[1][0]);

        $this->mailer = new Zend_Mail('utf-8');

        $this->mailer->setFrom($mailerFrom, $mailerFromName);
        $this->mailer->setSubject($subject);
        $this->mailer->addTo($to);
        $this->mailer->setBodyHtml($message, 'UTF-8', Zend_Mime::ENCODING_BASE64);

        if (!empty($attach)) {
            $logo = new Zend_Mime_part(file_get_contents($attach));
            $logo->type = $attach_type;
            $logo->disposition = Zend_Mime::DISPOSITION_INLINE;
            $logo->encoding = Zend_Mime::ENCODING_BASE64;
            $logo->filename = $name;

            $at = $this->mailer->addAttachment($logo);
        }
        try {
            $this->mailer->send();
        } catch (Exception $ex) {
            echo "Ошибка отправки электронного письма на ящик " . $to;
            exit;
        }
    }

    public function sendMail2($to, $message, $subject, $attach = '', $name = '', $attach_type = '')
    {
        require_once 'include/mail/Phpmailer.class.php';
        require_once 'include/mail/Smtp.class.php';

        $email_from = $this->getSettingValue('email_from');
        $patrern = '/(.*)<?([a-zA-Z0-9\-\_]+\@[a-zA-Z0-9\-\_]+(\.[a-zA-Z0-9]+?)+?)>?/U';
        preg_match($patrern, $email_from, $arr);

        $mailer = new PHPMailer();
        $mailer->Subject = $subject;
        $mailer->ContentType = 'plain/html';          // SMTP password
        $mailer->CharSet = 'utf-8';          // SMTP password
        $mailer->From = empty($arr[2]) ? '' : trim($arr[2]);
        $mailer->FromName = empty($arr[1]) ? '' : trim($arr[1]);
        $mailer->AddReplyTo('');
        $mailer->WordWrap = 50;
        $mailer->IsHTML(true);

        if (!empty($attach)) {
            $mailer->AddAttachment($attach, $name);
        }

        $mailer->Body = $message;
        $mailer->AddAddress($to);

        return $mailer->Send();
    }

    public function max_post_str()
    {
        $upload_max_lit = ini_get('upload_max_filesize');
        $lit = substr($upload_max_lit, -1);
        $upload_max = ini_get('upload_max_filesize') + 0;

        switch ($lit) {
            case 'M':
                return $upload_max . ' Mb.';
                break;

            case 'K':
                return $upload_max . ' Kb.';
                break;
        }
    }
    
    private function getLeftBanners()
    {
        $headers = $this->AnotherPages->getLeftBanners($this->lang_id);
        if (!empty($headers)) {
            foreach ($headers as $val) {
                $this->domXml->create_element('left_banner', '', 2);

                $this->domXml->create_element('url', $val['URL']);

                $this->setXmlNode($val['DESCRIPTION'], 'description');
                if (!empty($val['IMAGE']) && strchr($val['IMAGE'], "#")) {
                    $tmp = explode('#', $val['IMAGE']);
                    $this->domXml->create_element('image', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0]
                        , 'w' => $tmp[1]
                        , 'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }

                if (!empty($val['IMAGE1']) && strchr($val['IMAGE1'], "#")) {
                    $tmp = explode('#', $val['IMAGE1']);
                    $this->domXml->create_element('image_alt_text', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0]
                        , 'w' => $tmp[1]
                        , 'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }

                $this->domXml->go_to_parent();
            }
        }
    }

    /**
     * Получить телефон для подмены
     *
     * @return bool
     */
    private function refererPhones()
    {
        $refererUri = $this->getRequest()->getHeader('referer');
        $phone = '';
        if (!empty($refererUri)) {
            $urlInfo = parse_url($refererUri);

            if ($urlInfo['host'] == $_SERVER['HTTP_HOST']) {
                return false;
            }

            if (!empty($urlInfo['query'])) {
                parse_str($urlInfo['query'], $getData);
                $phone = $this->initQueryPhone($getData);
            }

            if (empty($urlInfo['query']) || empty($phone)) {
                $phone = $this->initDommenPhone($urlInfo['host']);
            }
        }

        if (!empty($phone)) {
            setcookie("referer_phone", $phone, time()+3600 * 60 * 2, '/');
            $this->domXml->set_tag('//page', true);
            $this->domXml->create_element('referer_phone', $phone, 1);
        }
    }

    /**
     * Проверить телефон по URI
     *
     * @param array $getData массив URI
     *
     * @return null|string
     */
    private function  initQueryPhone($getData)
    {
        $getDataKeys = array_keys($getData);
        $referer = $this->AnotherPages->getRefererPhones();

        if (!empty($referer)) {
            foreach ($referer as $view) {
                $criteria = explode(',', $view['CRITERIA']);
                $criteria = array_map('trim', $criteria);

                foreach ($criteria as $uriKey) {
                    $isPhone = false;
                    if (in_array($uriKey, $getDataKeys)) {
                        $isPhone = true;
                    }
                }

                if ($isPhone) {
                    return $view['PHONE'];
                }
            }
        }

        return null;
    }

    /**
     * Проверить телефон по домену
     *
     * @param string $domen домен реферара
     *
     * @return null|string
     */
    private function  initDommenPhone($domen)
    {
        $referer = $this->AnotherPages->getRefererPhones();

        if (!empty($referer)) {
            foreach ($referer as $view) {
                $domens = explode(',', $view['DOMENS']);
                $domens = array_map('trim', $domens);
                if (in_array($domen, $domens)) {
                    return $view['PHONE'];
                }
            }
        }

        return null;
    }

    protected function getMailTrasportData()
    {
        $mailTransportConfig['port'] = 25;
        $mailTransportConfig['auth'] = 'login';
        $mailTransportConfig['username'] = $this->getSettingValue('mail_transport_username');
        $mailTransportConfig['password'] = $this->getSettingValue('mail_transport_password');
        $mailTransportConfig['host'] = $this->getSettingValue('mail_transport_host');

        return $mailTransportConfig;
    }

    /**
     * @param int    $page     страница
     * @param int    $pcount   кол-во страниц
     * @param string $fileName URL раздела
     *
     * @return array
     */
    protected function getNexrPrevRel($page, $pcount, $fileName)
    {
        $relPrev = $relNext ='';

        $nextPage = $page + 1;
        $prevPage = $page - 1;

        if ($page == 1) {
            $relNext = 'http://'.$_SERVER['HTTP_HOST'].$fileName.'page/'.$nextPage.'/';
        } elseif ($page > 1 && $page < $pcount) {
            $relNext = 'http://'.$_SERVER['HTTP_HOST'].$fileName.'page/'.$nextPage.'/';
            $relPrev = 'http://'.$_SERVER['HTTP_HOST'].$fileName.'page/'.$prevPage.'/';
        } elseif ($page > 1 && $page == $pcount) {
            $relPrev = 'http://'.$_SERVER['HTTP_HOST'].$fileName.'page/'.$prevPage.'/';
        }

        return array($relPrev, $relNext);
    }

    private function getSokobamLevels()
    {
        $levels = $this->AnotherPages->getSokobamLevels();
        if (!empty($levels)) {
            foreach ($levels as $val) {
                $this->domXml->create_element('sokoban_levels', '', 2);

                $this->domXml->create_element('level', $val);
                $this->domXml->go_to_parent();
            }
        }
    }
}
