<?php
class Core_Controller_Router_Rout
{
    private $_router;

    public function __construct($router)
    {
        $this->_router = $router;
    }

    public function setRouting()
    {

    }

    public function getRouter()
    {
        return $this->_router;
    }
} 