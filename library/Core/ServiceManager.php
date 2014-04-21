<?php
class Core_ServiceManager
{
    /**
     * @var Core_ServiceManager
     */
    protected $serviceManager;

    public function __construct(Core_ServiceManager_Basic $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @return Core_ServiceManager_Models
     */
    public function getModel()
    {
        return $this->serviceManager['core.models'];
    }

    /**
     * @return Core_ServiceManager_Helpers
     */
    public function getHelper()
    {
        return $this->serviceManager['core.helpres'];
    }
} 