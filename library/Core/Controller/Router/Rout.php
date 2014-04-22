<?php
class Core_Controller_Router_Rout
{
    private $_router;

    /**
     * @var Core_ServiceManager
     */
    private $_serviceManager;

    /**
     * @param $router
     * @param Core_ServiceManager $serviceManager
     */
    public function __construct($router, $serviceManager)
    {
        $this->_router = $router;
        $this->_serviceManager = $serviceManager;
    }

    public function setRouting()
    {
        $this->_initContorollerDefault();

        $this->_initAliasingAjax();
//        $this->_initAliasingDoc();
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
                'action' => 'view'
            ),
            array(
                1 => 'n'
            )
        );

        $this->_router->addRoute('doc', $routed);

        $routed_lang = new Zend_Controller_Router_Route_Regex(
            '(\w{2})/doc/(.+)\.html',
            array(
                'controller' => 'doc',
                'action' => 'view'
            ),
            array(
                1 => 'lang',
                2 => 'n'

            )
        );

        $this->_router->addRoute('doc_multilingual', $routed_lang);

// $values3 = $routed_lang->match('/en/doc/prodvizhenie-sajtov.html');
// print_r($values3);
// exit;
    }
} 