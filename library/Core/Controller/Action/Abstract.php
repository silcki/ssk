<?php
abstract class Core_Controller_Action_Abstract extends Zend_Controller_Action
{
    /**
     * @var Core_DomXML
     */
    public $domXml;

    /**
     * @var string
     */
    public $lang;

    /**
     * @var int
     */
    public $lang_id;

    public $base;

    /**
     * @var AnotherPages
     */
    protected $AnotherPages;

    public $Article;
    public $Brands;
    public $Clients;

    public $SystemSets;
    public $GoodsGroup;
    public $SectionAlign;
    public $Item;
    public $Textes;
    public $Gallery;

    public $News;

    private $print;

    public $def_lang;
    public $curURI;
    public $lang_panel;
    public $template;
    public $work_controller;
    public $work_action;
    public $befor_path = array();
    public $after_path = array();

    /**
     * @var Core_ServiceManager
     */
    protected $serviceManager;

    /**
     * @var Zend_Controller_Request_Http
     */
    protected $requestHttp;

    public function init()
    {
        $this->work_controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
        $this->work_action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();
        $this->serviceManager = \Zend_Registry::get('serviceManager');

        $this->requestHttp = new Zend_Controller_Request_Http();
        $this->domXml = $this->view->getDomXml();

        $this->cmf = Zend_Controller_Front::getInstance()->getParam('cmf');

        $this->curURI = $this->requestHttp->getRequestUri();

        preg_match("/\/print\/([^\/]*)/", $this->curURI, $m1);
        if (!empty($m1)) {
            $this->print = $m1[1];
        }

        $this->domXml->create_element('page', "", 1);
        if ($this->print == 'yes') {
            $this->domXml->set_attribute(array('print' => $this->print));
        }

//        $this->domXml->create_element('currentURL', $this->curURI, 1);
        $this->domXml->set_tag('//page', true);

        $this->AnotherPages = $this->getServiceManager()->getModel()->getAnotherPages();

        if ($this->print) {
            $this->getSysText('print_top_right_text');
            $this->domXml->create_element('referer', $_SERVER['SERVER_NAME'], 1);
        }

        $this->refererPhones();
        $this->getSokobamLevels();
    }

    /**
     * Disable Renderer.
     *
     * @return Core_Controller_Action_Abstract
     */
    protected function _disableRender()
    {
        if($this->_helper->hasHelper('viewRenderer'))
        {
            $this->_helper->viewRenderer->setNoRender(true);
        }
        return $this;
    }

    public function preDispatch()
    {
        $this->initLangs();

        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;
        $params['curURI'] = $this->curURI;

        $this->domXml->set_tag('//page', true);
        $this->getOurBanner();

        $this->getServiceManager()->getHelper()->getLanguages()
            ->setParams($params)
            ->getLanguageInfo()
            ->getLangs();

        $this->domXml->set_tag('//page', true);

        $this->getServiceManager()->getHelper()->getBanners()
            ->setParams($params)
            ->getBanners();

        $this->getServiceManager()->getHelper()->getSystemTextes()->getTextes();
        $this->getServiceManager()->getHelper()->getFileTypes()->getFileTypes();

        $params['itemId'] = $this->_getParam('it', null);
        $this->getServiceManager()->getHelper()->getCatalogue()
            ->setParams($params)
            ->initPathIDs($this->_getParam('catalog_id', null))
            ->getCatTree();

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->initPathIDs($this->_getParam('doc_id', null))
            ->getLeftBanners()
            ->makeMenu(1);

        $siteVote = null;
        if ($this->requestHttp->has('sklad_vote')) {
            $siteVote = $this->requestHttp->get('sklad_vote');
        }

        $this->getServiceManager()->getHelper()->getVopros()
            ->setParams($params)
            ->getVopros($siteVote);
    }

    private function initLangs()
    {
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
            if ($this->requestHttp->get('site_first')) {
                $first = $this->requestHttp->get('site_first');
            } else {
                $first = '';
            }

            if ($first == '') {
                //Первый заход
                if ($this->requestHttp->get('site_lang')) {
                    $this->lang = $this->requestHttp->get('site_lang');
                } else {
                    $this->lang = '';
                }

                setcookie("site_lang", "", time() - 3600);
                setcookie("site_lang", $this->lang, time() + 3600 * 24 * 3, "/");
                setcookie("site_first", "1", 0, "/");
            } else {
                if ($this->requestHttp->get('site_lang')) {
                    $this->lang = $this->requestHttp->get('site_lang');
                } else {
                    $this->lang = '';
                }

                setcookie("site_lang", "", time() - 3600);
                setcookie("site_lang", $this->lang, time() + 3600 * 24 * 3, "/");
            }
        }

        $this->lang_id = $this->getLanguageId($this->lang);
    }

    /**
     * @return Core_ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }


    public function postDispatch()
    {
        if ($this->print) {
            $this->view->setScriptPath('print.xsl');
        }
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

    /**
     * Метод вывода страницы 404
     */
    public function page_404()
    {
        $this->getResponse()->setHttpResponseCode(404);
        $this->getResponse()->sendHeaders();
        echo "<h1>404 Not found!</h1>";
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
        return $this->getServiceManager()->getModel()->getSystemSets()->getSettingValue($name);
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
        if (isset($_SESSION['ses_user_id']) && !empty($_SESSION['ses_user_id'])) {
            return true;
        } else {
            return false;
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
        $pattern = '/(.*)<?([a-zA-Z0-9\-\_]+\@[a-zA-Z0-9\-\_]+(\.[a-zA-Z0-9]+?)+?)>?/U';
        preg_match_all($pattern, $email_from, $arr);

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
        $anotherPagesModel = $this->getServiceManager()->getModel()->getAnotherPages();

        $levels = $anotherPagesModel->getSokobamLevels();
        if (!empty($levels)) {
            foreach ($levels as $val) {
                $this->domXml->create_element('sokoban_levels', '', 2);

                $this->domXml->create_element('level', $val);
                $this->domXml->go_to_parent();
            }
        }
    }
}