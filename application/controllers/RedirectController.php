<?php

class RedirectController extends CommonBaseController
{

    function init()
    {
        parent::init();

        Zend_Controller_Front::getInstance()->getRequest()->setActionName('all');
    }

    public function allAction()
    {
        $url = '';
        if ($this->_hasParam('url'))
            $url = $this->_getParam('url');

        if (!empty($url)) {
            $pattern = '/^(http:\/\/|https:\/\/)?(.+)/i';
            preg_match($pattern, $url, $out);

            if (empty($out[1]))
                $url = 'http://' . $url;
            $this->_redirector->gotoUrl($url);
        }
    }

}

?>
