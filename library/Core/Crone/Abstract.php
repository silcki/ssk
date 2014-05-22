<?php
class Core_Crone_Abstract
{
    /**
     * @var Core_ServiceManager
     */
    protected $serviceManager;

    public function __construct()
    {
        $this->serviceManager  = \Zend_Registry::get('serviceManager');

        $this->init();
    }

    public function init() {}

    /**
     * @return Core_ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }
} 