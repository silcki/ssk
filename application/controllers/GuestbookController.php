<?php

class GuestbookController extends CommonBaseController
{

    public $Guestbook;
    public $is_post = false;
    public $order = array();
    public $error = array();

    function init()
    {
        parent::init();
        $this->makeMenu(5);

        Zend_Loader::loadClass('Guestbook');
        $this->Guestbook = new Guestbook();

        $http = new Zend_Controller_Request_Http();

        Zend_Controller_Front::getInstance()->getRequest()->setActionName('all');

        if ($http->isPost()) {
            if ($http->has('name') && $http->getPost('name')) {
                $this->order['NAME'] = $http->getPost('name');
            }

            if ($http->has('city'))
                $this->order['CITY'] = $http->getPost('city');

            if ($http->has('goods'))
                $this->order['GOODS'] = $http->getPost('goods');

            if ($http->has('description'))
                $this->order['DESCRIPTION'] = $http->getPost('description');

            if ($http->has('capcha') && $http->getPost('capcha')) {
                $capcha = $http->getPost('capcha');
            } else {
                $this->error[count($this->error)] = 'Контрольное число пустое';
            }

            if (!empty($capcha)) {
                if ($capcha != $_SESSION['biz_captcha']) {
                    $this->error[count($this->error)] = 'Не верно указано контрольное число';
                }
            }

            $this->is_post = true;
        }
    }

    public function allAction()
    {
        $this->getCatTree();
        $this->getDocMeta();

        $o_data['is_vote'] = '';
        $this->openData($o_data);

        if ($this->is_post) {
            if (empty($this->error) && $this->is_post) {
                $this->order['POSTED_AT'] = date("Y-m-d H:i:s");
                $this->order['STATUS'] = 0;
                $this->Guestbook->insertMessage($this->order);

                $this->_redirector->gotoUrl('/guestbook/all/');
            } elseif (!empty($this->error) && $this->is_post) {
                $this->viewErrors($this->error);
                $this->sendErrorData($this->order);
            }
        }

        $this->domXml->set_tag('//data', true);

        $this->getMessages();
    }

    public function getDocMeta()
    {
        $this->domXml->create_element('docinfo', '', 2);
        $this->domXml->create_element('title', 'гостевая книга');

        $this->domXml->create_element('description', 'гостевая книга');

        $this->domXml->create_element('keywords', 'гостевая книга');

        $this->domXml->go_to_parent();
    }

    public function getMessages()
    {
        $messages = $this->Guestbook->getMessage();
        if (!empty($messages)) {
            $this->domXml->set_tag('//data', true);
            foreach ($messages as $view) {
                $this->domXml->create_element('messages_list', '', 2);
                $this->domXml->set_attribute(array('ann_id' => $view['ANN_ID']));

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('date', $view['date']);
                $this->domXml->create_element('city', $view['CITY']);
                $this->domXml->create_element('goods', $view['GOODS']);
                $this->domXml->create_element('description',
                                              $view['DESCRIPTION']);

                $this->domXml->go_to_parent();
            }
        }
    }

}
