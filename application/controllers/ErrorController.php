<?php
class ErrorController extends Core_Controller_Action_Abstract
{
    public function init()
    {
        parent::init();

        $this->domXml->create_element('page', '', 1);
        $this->template = "error";
    }

    public function errorAction()
    {
        Zend_Loader::loadClass('AnotherPages');
        $AnotherPages = new AnotherPages();

        $this->domXml->create_element('data', '', 1);

        $doc_id = $AnotherPages->getPageId('/404/');
        $info = $AnotherPages->getDocInfo($doc_id);
        if ($info) {
            $this->domXml->create_element('docinfo', '', 2);
            $this->domXml->set_attribute(array('id' => $info['ANOTHER_PAGES_ID']
            ));

            $this->domXml->create_element('name', $info['NAME']);
            $this->getServiceManager()->getHelper()->getAnotherPages()->getDocXml($doc_id, 0, true, 0);
        }

        $errors = $this->_getParam('error_handler');

        if (APPLICATION_ENV != 'production') {
            echo '<pre>';
            var_dump($errors->exception);
            exit;
        }

        $errors = $this->_getParam('error_handler');
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_OTHER:
                $this->getResponse()->setHttpResponseCode(404);
                break;
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
                $this->getResponse()->setHttpResponseCode(404);
                break;
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
                $this->getResponse()->setHttpResponseCode(404);
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                $this->domXml->create_element('message', 'Application error');
                break;
        }

        $this->getResponse()->sendHeaders();
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasPluginResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }

    /**
     * Выполняется перед ZEND-диспетчиризацией.
     * Обеспечивает невыполнение стандартного ZEND-рендеринга.
     */
    public function preDispatch()
    {
        $this->_disableRender();
    }
}