<?php
class Core_Application_Resource_View extends Zend_Application_Resource_View
{
    const CONF_DEFAULT_ENGINE = 'defaultEngine';
    const CONF_ENGINES = 'engines';
    const CONF_ENGINE_NAME = 'engine';

    public function init()
    {
        $options = $this->getOptions();
        $this->_view = new Core_Xslt($options);

        // setup viewRenderer with suffix and view
        /** @var Zend_Controller_Action_Helper_ViewRenderer $viewRenderer*/
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setViewSuffix('xsl');
        $viewRenderer->setView($this->_view);

        $layout = Zend_Layout::getMvcInstance();
        $layout->setViewSuffix('xsl');

        return $this->_view;
    }
}