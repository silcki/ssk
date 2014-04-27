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

    /**
     * @var AnotherPages
     */
    protected $AnotherPages;

    /**
     * @var string
     */
    protected $work_controller;

    /**
     * @var string
     */
    protected $work_action;

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

        $this->domXml->create_element('page', "", 1);
        $this->domXml->set_tag('//page', true);

        $this->AnotherPages = $this->getServiceManager()->getModel()->getAnotherPages();
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

        $this->refererPhones();
        $this->getSokobamLevels();

        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $this->domXml->set_tag('//page', true);

        $this->getServiceManager()->getHelper()->getLanguages()
            ->setParams($params)
            ->getLanguageInfo()
            ->getLangs($this->getRequest()->getRequestUri());

        $this->domXml->set_tag('//page', true);

        $this->getServiceManager()->getHelper()->getBanners()
            ->setParams($params)
            ->getBanners();

        $this->getServiceManager()->getHelper()->getSystemTextes()->getTextes();
        $this->getServiceManager()->getHelper()->getFileTypes()->getFileTypes();

        $params['itemId'] = $this->_getParam('it', null);
        $this->getServiceManager()->getHelper()->getCatalogue()
            ->setParams($params)
            ->getCatTree();

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->initPathIDs($this->getParam('doc_id', null))
            ->getLeftBanners()
            ->makeMenu(1);

        $siteVote = null;
        if ($this->requestHttp->has('sklad_vote')) {
            $siteVote = $this->requestHttp->get('sklad_vote');
        }

        $this->getServiceManager()->getHelper()->getVopros()
            ->setParams($params)
            ->getVopros($siteVote);

        $this->_getSysText();
    }

    protected function _getSysText(){}

    private function initLangs()
    {
        $this->lang = $this->getParam('lang', 'ru');
        $this->lang_id = $this->getLanguageId($this->lang);
    }

    /**
     * @return Core_ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }


    public function postDispatch() {}

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
            if ($prev < 1) {
                $start_number = 1;
            } elseif (($sectionInfo['pcount'] - $prev) < $cntMiddlePages - 1) {
                $start_number = $sectionInfo['pcount'] - $cntMiddlePages - 1;
            } else {
                $start_number = $prev;
            }


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
     * Метод вывода страницы 404
     */
    public function page_404()
    {
        $this->getResponse()->setHttpResponseCode(404);
        $this->getResponse()->sendHeaders();
        echo "<h1>404 Not found!</h1>";
        exit;
    }

    public function getSettingValue($name)
    {
        return $this->getServiceManager()->getModel()->getSystemSets()->getSettingValue($name);
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

    public function getMaxPostStr()
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

    protected function formProcessing($data)
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

    /**
     * Проверить телефон по URI
     *
     * @param array $getData массив URI
     *
     * @return null|string
     */
    private function initQueryPhone($getData)
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
    private function initDommenPhone($domen)
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