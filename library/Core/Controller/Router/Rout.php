<?php
class Core_Controller_Router_Rout
{
    private $_router;

    /**
     * @var Core_ServiceManager
     */
    private $_serviceManager;

    /**
     * @param Zend_Controller_Router_Interface $router
     * @param Core_ServiceManager              $serviceManager
     */
    public function __construct($router, $serviceManager)
    {
        $this->_router = $router;
        $this->_serviceManager = $serviceManager;
    }

    public function setRouting()
    {
        $this->_initChpu();

        $this->_initContorollerDefault();

        $this->_initAliasingAjax();
        $this->_initAliasingDoc();
    }

    private function _initChpu()
    {
        $request = new Zend_Controller_Request_Http();
        $uri = $request->getRequestUri();

        $anotherPagesModel = $this->getServiceManager()->getModel()->getAnotherPages();

        $is_page = false;
        $page = 1;
        $pattern_page = '/(.*)page\/(\d*)\//is';
        preg_match($pattern_page, $uri, $out);

        if (!empty($out[1])) {
            $is_page = true;
            $page = $out[2];
            $request->setRequestUri($out[1]);
        }

        $urlInfo = parse_url($_SERVER['REQUEST_URI']); // извлекаем части урла в массив

        $sefuByOld = $anotherPagesModel->getSefURLbyOldURL($urlInfo['path']); // получаем ЧПУ-урл на основе старого урла

        if (!empty($sefuByOld)) { // если существует ЧПУ-урл для старого урла, делаем 301 редирект со старого урла на ЧПУ
            $helper = new Zend_Controller_Action_Helper_Redirector();
            $helper->setCode(301);
            $helper->gotoUrlAndExit($sefuByOld);

        } else { // проверяем является ли пришедший урл ЧПУ-урлом из нашей базы
            $siteURLbySEFU = $anotherPagesModel->getSiteURLbySEFU($urlInfo['path']);

            if (!empty($siteURLbySEFU)) {  // если существует урл сайта для ЧПУ-урла, то формируем $_REQUEST['p_']
                if ($is_page) {
                    $siteURLbySEFU.='page/' . $page . '/';
                }

                $request->setRequestUri($siteURLbySEFU);
            }
        }

        $front = Zend_Controller_Front::getInstance();
        $front->setRequest($request);
    }

    /**
     * @return Core_ServiceManager
     */
    public function getServiceManager()
    {
        return $this->_serviceManager;
    }

    public function getRouter()
    {
        return $this->_router;
    }

    private function _initContorollerDefault() {
        $this->_router->addRoute('def', new Zend_Controller_Router_Route(
            ':controller',
            array(
                'controller' => 'index',
                'action' => 'index'
            )
        ));

        $this->_router->addRoute('default_multilang', new Zend_Controller_Router_Route(
            ':lang/:controller/:action/*',
            array(
                'controller' => 'index',
                'action' => 'index'
            ),
            array(
                'lang' => '\w{2}'
            )
        ));
    }

    private function _initAliasingAjax() {
        $this->_router->addRoute('ajax',
            new Zend_Controller_Router_Route(
                'ajax/:action',
                array(
                    'controller' => 'ajax'
                )
            ));
    }

    private function _initAliasingDoc() {
        $routed = new Zend_Controller_Router_Route_Regex(
            'doc/(.+)',
            array(
                'controller' => 'doc',
                'action' => 'index'
            ),
            array(
                1 => 'doc_id'
            )
        );

        $this->_router->addRoute('doc', $routed);

        $routed_lang = new Zend_Controller_Router_Route_Regex(
            '(\w{2})/doc/(.+)\.html',
            array(
                'controller' => 'doc',
                'action' => 'index'
            ),
            array(
                1 => 'lang',
                2 => 'doc_id'

            )
        );

        $this->_router->addRoute('doc_multilingual', $routed_lang);

// $values3 = $routed_lang->match('/en/doc/prodvizhenie-sajtov.html');
// print_r($values3);
// exit;
    }
} 