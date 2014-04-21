<?php
class Core_Application_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * @var Core_ServiceManager
     */
    protected $serviceManager;

    protected function _initDatabase(){
        $config = $this->getOptions();

        $db = Zend_Db::factory($config['resources']['db']['adapter'], $config['resources']['db']['params']);

        //set default adapter
        Zend_Db_Table::setDefaultAdapter($db);

        //save Db in registry for later use
        Zend_Registry::set("db", $db);
    }

    protected function _initAutoload()
    {
        $loader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => APPLICATION_PATH));

        return $loader;
    }

    protected function _initRouter()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        $rout = new Core_Controller_Router_Rout($router);
        $rout->setRouting();

        return $rout->getRouter();
    }

    protected function _initServiceManager()
    {
        $serviceManager = new Core_ServiceManager(new Core_ServiceManager_Basic());

        \Zend_Registry::set('serviceManager', $serviceManager);
        $this->serviceManager = $serviceManager;

        return $serviceManager;
    }

    /**
     * @return BasicServiceManagerFacade
     */
    public function getServiceManager()
    {
        $this->bootstrap('serviceManager');
        return $this->serviceManager;
    }
}