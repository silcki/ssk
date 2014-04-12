<?php

class ErrorController extends Zend_Controller_Action
{

    protected $domXml;
    protected $template;

    function init()
    {
        parent::init();

        $this->domXml = &$this->view->serializer;
        $this->domXml->create_element('page', '', 1);
        $this->template = "error";
    }

    public function errorAction()
    {
        Zend_Loader::loadClass('AnotherPages');
        $AnotherPages = new AnotherPages();
//        $this->xml = $this->view->serializer;

        $this->domXml->create_element('data', '', 1);

        $doc_id = $AnotherPages->getPageId('/404/');
        $info = $AnotherPages->getDocInfo($doc_id);
        if ($info) {
            $this->domXml->create_element('docinfo', '', 2);
            $this->domXml->set_attribute(array('id' => $info['ANOTHER_PAGES_ID']
            ));

            $this->domXml->create_element('name', $info['NAME']);
            $this->getDocXml($doc_id, 0, true, 0);
        }

//        echo '<pre>';
//        var_dump($errors->exception);
//        exit;

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



//        $this->postDispatch();
        // Log exception, if logger available
//        if ($log = $this->getLog()) {
//            $log->crit($this->view->message, $errors->exception);
//        }
        // conditionally display exceptions
//        if ($this->getInvokeArg('displayExceptions') == true) {
//             $this->xml->create_element('exception','',1);
//             $this->xml->create_element('message',$errors->exception->getMessage(),0);
//
//             $this->xml->create_element('TraceString',$errors->exception->getTraceAsString(),0);
//
//
//        }
//        $this->xml->create_element('request',var_export($errors->request->getParams(),true),0);
//
    }

    public function getDocXml($id = 0, $type = 0, $tag = false, $lang = 0)
    {
        $AnotherPages = new AnotherPages();

        $doc = $AnotherPages->getDocXml($id, $type, $lang);
        $doc = stripslashes($doc);


        if (!empty($doc)) {
            $pattern = '/<a(.+)href="([http:\/\/|https:\/\/]?.+)"/Uis';
            $doc = preg_replace_callback($pattern, 'addSizeText', $doc);

            $pattern = '/(<a.*href=".*\.(\w+)) size=(.+)"/Uis';
            $doc = preg_replace($pattern, '${1}" typeDoc="$2" size="$3"', $doc);

            if ($tag) {
                $txt = "<?xml version=\"1.0\" encoding=\"{$this->domXml->get_encoding()}\"?><!DOCTYPE stylesheet SYSTEM \"symbols.ent\"><txt>" . $doc . "</txt>";
            } else {
                $txt = "<?xml version=\"1.0\" encoding=\"{$this->domXml->get_encoding()}\"?><!DOCTYPE stylesheet SYSTEM \"symbols.ent\">" . $doc;
            }
            $this->domXml->import_node($txt, false);
        }
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

    public function postDispatch()
    {
        if ($this->template)
            $template = $this->template . ".xsl";
//        else
//            $template = $this->work_controller . '_' . $this->work_action . '.xsl';

        $path_ = $this->view->getScriptPath($template);
        if (!is_file($path_)) {
            $path_ = $this->work_controller . '.xsl';
        } else {
            $path_ = $template;
        }


        echo $this->view->render($path_);
    }

    /**
     * Выполняется перед ZEND-диспетчиризацией.
     * Обеспечивает невыполнение стандартного ZEND-рендеринга.
     */
    public function preDispatch()
    {
        // disable view script autorendering
        // we created own render
        $this->_helper->viewRenderer->setNoRender();
    }

}

