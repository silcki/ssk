<?php

class ClientsController extends CommonBaseController
{

    public $doc_id;

    function init()
    {
        parent::init();

        $http = new Zend_Controller_Request_Http();

        Zend_Loader::loadClass('Clients');
        $this->Clients = new Clients();

        $this->getSysText('page_main');

        $action_name = Zend_Controller_Front::getInstance()->getRequest()->getActionName();


        if ($action_name != 'view')
            Zend_Controller_Front::getInstance()->getRequest()->setActionName('all');
    }

    public function allAction()
    {

        $ap_id = $this->AnotherPages->getPageId('/clients/');

        $this->getDocMeta($ap_id);

        $o_data['ap_id'] = $ap_id;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getDocInfo($ap_id);

        $this->getDocPath($ap_id);
        $this->getClients();
    }

    public function viewAction()
    {
        if ($this->_hasParam('n'))
            $id = $this->_getParam('n');

        $ap_id = $this->AnotherPages->getPageId('/clients/');

        $this->getClientMeta($id);

        $o_data['ap_id'] = $ap_id;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getDocInfo($ap_id);

        $this->getDocMeta($ap_id);

        $this->domXml->create_element('client_data', '', 2);
        $this->getDocXml($id, 8, true, $this->lang_id);
    }

    public function getDocMeta($id)
    {
        $info = $this->AnotherPages->getDocInfo($id, $this->lang_id);
        if ($info) {
            $this->domXml->create_element('docinfo', '', 2);
            $this->domXml->create_element('name', $info['NAME']);

            $this->domXml->create_element('title', $info['TITLE']);

            $descript = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;",
                                     $info['DESCRIPTION']);
            $descript = preg_replace("/\"/", "&#171;", $descript);
            $this->domXml->create_element('description', $descript);

            $keyword = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;",
                                    $info['KEYWORDS']);
            $keyword = preg_replace("/\"/", "&#171;", $keyword);
            $this->domXml->create_element('keywords', $keyword);

            $this->domXml->go_to_parent();
        }
    }

    private function getClientMeta($id)
    {
        $info = $this->Clients->getClientsInfo($id, $this->lang_id);
        if ($info) {
            $this->domXml->create_element('docinfo', '', 2);
            $this->domXml->create_element('name', $info['NAME']);

            $this->domXml->create_element('title', $info['NAME']);

            $descript = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;",
                                     $info['DESCRIPTION']);
            $descript = preg_replace("/\"/", "&#171;", $descript);
            $this->domXml->create_element('description', $descript);

            $keyword = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;",
                                    $info['DESCRIPTION']);
            $keyword = preg_replace("/\"/", "&#171;", $keyword);
            $this->domXml->create_element('keywords', $keyword);

            $this->domXml->go_to_parent();
        }
    }

    private function getClients()
    {
        $clients = $this->Clients->getClients($this->lang_id);
        if ($clients) {
            $i_td = 0;
            foreach ($clients as $view) {
                if (($i_td > 0) && ($i_td % 4) == 0) {
                    $this->domXml->go_to_parent();
                }

                if (($i_td % 4) == 0) {
                    $this->domXml->create_element('clients_tr', '', 2);
                }

                $this->domXml->create_element('clients', '', 2);
                $this->domXml->set_attribute(array('client_id' => $view['CLIENT_ID']
                ));

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('email', $view['EMAIL']);
                $this->domXml->create_element('url', $view['URL']);
                $this->domXml->create_element('description',
                                              $view['DESCRIPTION']);

                if (!empty($view['IMAGE1']) && strchr($view['IMAGE1'], "#")) {
                    $tmp = split('#', $view['IMAGE1']);
                    $this->domXml->create_element('image1', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0]
                        , 'w' => $tmp[1]
                        , 'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }

                $doc = $this->AnotherPages->getDocXml($view['CLIENT_ID'], 8,
                                                      $this->lang_id);
                if (!empty($doc)) {
                    $this->domXml->create_element('is_doc', 1);
                }

                $this->domXml->go_to_parent();

                $i_td++;
            }
        }
    }

}