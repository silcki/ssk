<?php

class RedirectController extends Zend_Controller_Action
{
    public function indexAction()
    {
        $url = $this->getParam('url');

        if (!empty($url)) {
            $pattern = '/^(http:\/\/|https:\/\/)?(.+)/i';
            preg_match($pattern, $url, $out);

            if (empty($out[1])) {
                $url = 'http://' . $url;
            }

            $this->redirect($url);
        }
    }
}