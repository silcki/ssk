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
        $this->_initAliasingNews();
        $this->_initAliasingClients();
        $this->_initAliasingArticles();
        $this->_initAliasingGallary();
        $this->_initAliasingVideoGallary();
    }

    private function _initChpu()
    {
        $request = new Zend_Controller_Request_Http();
        $uri = $request->getRequestUri();

        if ($uri == '/') {
            return true;
        }

        $anotherPagesModel = $this->getServiceManager()->getModel()->getAnotherPages();

        $is_product_type = $is_scope = $is_country = $is_page = false;
        $page = 1;
        $pattern_page = '/(.*)(country\/(.+)\/)?(scope\/(.+)\/)?(product_type\/(.+)\/)?(page\/(\d*)\/)?$/Uis';

        if(preg_match($pattern_page, $uri, $out)){
            $country = !empty($out[3]) ? $out[3]:'';
            $scope = !empty($out[5]) ? $out[5]:'';
            $product_type = !empty($out[7]) ? $out[7]:'';
            $page = !empty($out[9]) ? $out[9]:1;

            if(!empty($page)) $is_page = true;
            if(!empty($country)) $is_country = true;
            if(!empty($scope)) $is_scope = true;
            if(!empty($product_type)) $is_product_type = true;

            $uri = !empty($out[1]) ? $out[1]:$uri;
        }

        $urlInfo = parse_url($uri); // извлекаем части урла в массив

        $sefuByOld = $anotherPagesModel->getSefURLbyOldURL($urlInfo['path']); // получаем ЧПУ-урл на основе старого урла

        if (!empty($sefuByOld)) { // если существует ЧПУ-урл для старого урла, делаем 301 редирект со старого урла на ЧПУ
            $helper = new Zend_Controller_Action_Helper_Redirector();
            $helper->setCode(301);
            $helper->gotoUrlAndExit($sefuByOld);

        } else { // проверяем является ли пришедший урл ЧПУ-урлом из нашей базы
            $siteURLbySEFU = $anotherPagesModel->getSiteURLbySEFU($urlInfo['path']);

            if (!empty($siteURLbySEFU)) {  // если существует урл сайта для ЧПУ-урла, то формируем $_REQUEST['p_']
                if($is_page) $siteURLbySEFU.='page/'.$page.'/';
                if($is_country) $siteURLbySEFU.='country/'.$country.'/';
                if($is_scope) $siteURLbySEFU.='scope/'.$scope.'/';
                if($is_product_type) $siteURLbySEFU.='product_type/'.$product_type.'/';

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
            '(\w{2})/doc/(.+)',
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

    private function _initAliasingNews() {

        $routed = new Zend_Controller_Router_Route_Regex(
            'news/page/(\d+)',
            array(
                'controller' => 'news',
                'action' => 'index'
            ),
            array(
                1 => 'page'
            )
        );

        $this->_router->addRoute('news', $routed);

        $routed = new Zend_Controller_Router_Route_Regex(
            'news/view/n/(\d+)',
            array(
                'controller' => 'news',
                'action' => 'view'
            ),
            array(
                1 => 'n'
            )
        );

        $this->_router->addRoute('news_view', $routed);

        $routed_lang = new Zend_Controller_Router_Route_Regex(
            '(\w{2})/news/view/n/(\d+)',
            array(
                'controller' => 'news',
                'action' => 'view'
            ),
            array(
                1 => 'lang',
                2 => 'n'

            )
        );

        $this->_router->addRoute('news_view_multilingual', $routed_lang);
    }

    private function _initAliasingClients() {

        $routed = new Zend_Controller_Router_Route_Regex(
            'clients',
            array(
                'controller' => 'clients',
                'action' => 'index'
            )
        );

        $this->_router->addRoute('clients', $routed);

        $routed = new Zend_Controller_Router_Route_Regex(
            'clients(/country/([^/].*?))?(/scope/([^/].*?))?(/product_type/([^/].*?))?',
            array(
                'controller' => 'clients',
                'action' => 'index'
            ),
            array(
                2 => 'countryId',
                4 => 'scopeId',
                6 => 'productTypeId',
            )
        );

        $this->_router->addRoute('clients_filter', $routed);

//        $values3 = $routed->match('/clients/country/1,2/scope/3,4/product_type/5,6/');
//        var_dump($values3);
//        exit;

        $routed_lang = new Zend_Controller_Router_Route_Regex(
            '(\w{2})/clients',
            array(
                'controller' => 'clients',
                'action' => 'index'
            ),
            array(
                1 => 'lang'
            )
        );

        $this->_router->addRoute('clients_view_multilingual', $routed_lang);
    }

    private function _initAliasingArticles() {

        $routed = new Zend_Controller_Router_Route_Regex(
            'articles/page/(\d+)',
            array(
                'controller' => 'articles',
                'action' => 'index'
            ),
            array(
                1 => 'page'
            )
        );

        $this->_router->addRoute('articles', $routed);

        $routed = new Zend_Controller_Router_Route_Regex(
            'articles/view/n/(\d+)',
            array(
                'controller' => 'articles',
                'action' => 'view'
            ),
            array(
                1 => 'n'
            )
        );

        $this->_router->addRoute('articles_view', $routed);

        $routed_lang = new Zend_Controller_Router_Route_Regex(
            '(\w{2})/articles/view/n/(\d+)',
            array(
                'controller' => 'news',
                'action' => 'view'
            ),
            array(
                1 => 'lang',
                2 => 'n'

            )
        );

        $this->_router->addRoute('articles_view_multilingual', $routed_lang);
    }

    private function _initAliasingGallary() {
        $routed = new Zend_Controller_Router_Route_Regex(
            'gallery/all/n/(\d*)',
            array(
                'controller' => 'gallery',
                'action' => 'index'
            ),
            array(
                1 => 'n'
            )
        );

        $this->_router->addRoute('gallery_index', $routed);

        $routed_lang = new Zend_Controller_Router_Route_Regex(
            '(\w{2})/gallery/all/n/(\d*)',
            array(
                'controller' => 'gallery',
                'action' => 'index'
            ),
            array(
                1 => 'lang',
                2 => 'n'

            )
        );

        $this->_router->addRoute('gallery_index_multilingual', $routed_lang);
    }

    private function _initAliasingVideoGallary() {
        $routed = new Zend_Controller_Router_Route_Regex(
            'videogallery/all/n/(\d*)',
            array(
                'controller' => 'videogallery',
                'action' => 'index'
            ),
            array(
                1 => 'n'
            )
        );

        $this->_router->addRoute('videogallery_index', $routed);

        $routed_lang = new Zend_Controller_Router_Route_Regex(
            '(\w{2})/videogallery/all/n/(\d*)',
            array(
                'controller' => 'videogallery',
                'action' => 'index'
            ),
            array(
                1 => 'lang',
                2 => 'n'

            )
        );

        $this->_router->addRoute('videogallery_index_multilingual', $routed_lang);
    }
} 