<?php
class IndexController extends Core_Controller_Action_Abstract
{
    public function preDispatch()
    {
        parent::preDispatch();

        if ($this->requestHttp->get('play_bool')) {
            $this->domXml->set_tag('//page', true);
            $this->domXml->set_attribute(array('play_bool' => 1));
            $this->domXml->go_to_parent();
        }

        $this->getSysText();
    }

    private function getSysText()
    {
        $textes = array(
            'page_main',
            'arc_vote',
            'all_news',
            'play_againe',
            'text_our_clients',
            'text_index_news',
        );

        $systemTextes = $this->getServiceManager()->getHelper()->getSystemTextes();
        foreach ($textes as $indent) {
            $systemTextes->getSysText($indent);
        }
    }

    public function indexAction()
    {
        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $o_data['id'] = 0;
        $o_data['is_start'] = 1;
        $o_data['is_vote'] = 0;
        $o_data['is_start'] = 1;
        $this->openData($o_data);

        $this->domXml->set_tag('//data', true);
        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getHeader()
            ->getDocMeta(1)
            ->getDocInfo(1)
            ->getDocXml($this->AnotherPages->getPageId('/'), 0, true);

        $this->getServiceManager()->getHelper()->getClients()
            ->setParams($params)
            ->getClients();

        $news_index_amount = $this->getSettingValue('news_index_amount') ? $this->getSettingValue('news_index_amount'):2;

        $this->getServiceManager()->getHelper()->getNews()
            ->setParams($params)
            ->getIndexNews($news_index_amount);
    }

    public function voteAction()
    {
        $voprosModel = $this->getServiceManager()->getModel()->getVopros();

        $params['langId'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $docId = $this->AnotherPages->getPageId('/index/vote/');

        $o_data['id'] = 0;
        $o_data['is_vote'] = 1;
        $this->openData($o_data);

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocMeta($docId)
            ->getDocInfo($docId);

        if ($this->requestHttp->isPost()) {
            $opr = $this->requestHttp->getPost('opr', null);

            $vopros_id = $voprosModel->getVoprosID($opr);
            $voprosModel->voprosUp($vopros_id);
            $voprosModel->otvetUp($opr);

            setcookie("sklad_vote", $vopros_id, time() + 3600 * 24 * 3, "/");

            $this->redirect('/index/vote/');
        }

        $this->befor_path[0]['name'] = 'Архив голосований';
        $this->befor_path[0]['url'] = '';

        $this->getDocPath($docId);

        $this->getServiceManager()->getHelper()->getVopros()
            ->setParams($params)
            ->getAllVote();
    }

    public function clientvoteAction()
    {
        $client = $this->_getParam('client');
        if ($this->requestHttp->isPost()) {
            $this->Vopros->setClientVopros($client);

            $this->redirect('/index/votesuccess/');
        }

        $o_data['id'] = 1;
        $o_data['is_start'] = 0;
        $o_data['is_vote'] = 1;
        $this->openData($o_data);

        $site_client_vote = $this->Vopros->getClientVoprosClient($client);

        if ($site_client_vote == 0) {
            $doc_id = $this->AnotherPages->getPageId('/index/votefinish/');
            $info = $this->AnotherPages->getDocInfo($doc_id);
            if ($info) {
                $this->domXml->set_tag('//page', true);
                $this->domXml->create_element('docinfo', '', 2);
                $this->domXml->set_attribute(array('another_pages_id' => $info['ANOTHER_PAGES_ID']
                    , 'parent_id' => $info['PARENT_ID']
                ));

                $this->domXml->create_element('name', $info['NAME']);
                $this->getDocXml($doc_id, 0, true, 0);
            }
        } elseif ($site_client_vote == 1) {
            $this->domXml->create_element('client', $client, 2);
            $this->domXml->go_to_parent();

            $vopros = $this->Vopros->getClientVopros($client);

            if (!empty($vopros)) {
                $this->domXml->create_element('vopros', '', 2);
                $this->domXml->set_attribute(array('id' => $vopros['CLIENT_VOPROS_ID']));

                $this->domXml->create_element('name', $vopros['NAME']);

                $otvets = $this->Vopros->getClientOtvets($vopros['CLIENT_VOPROS_ID']);
                if (!empty($otvets)) {
                    foreach ($otvets as $view) {
                        $this->domXml->create_element('otvets', '', 2);

                        $this->domXml->set_attribute(array('id' => $view['CLIENT_OTVETS_ID']));

                        $this->domXml->create_element('name', $view['NAME']);

                        $this->domXml->go_to_parent();
                    }
                }

                $this->domXml->go_to_parent();
            }
        }
    }

    public function votesuccessAction()
    {
        $o_data['id'] = 1;
        $o_data['is_start'] = 0;
        $o_data['is_vote'] = 1;
        $this->openData($o_data);

        $doc_id = $this->AnotherPages->getPageId('/index/votesuccess/');
        $info = $this->AnotherPages->getDocInfo($doc_id);
        if ($info) {
            $this->domXml->set_tag('//data', true);
            $this->domXml->create_element('docinfo', '', 2);
            $this->getDocXml($doc_id, 0, true);
            $this->domXml->go_to_parent();

            $this->domXml->set_tag('//page', true);
            $this->domXml->create_element('docinfo', '', 2);
            $this->domXml->set_attribute(array(
                'another_pages_id' => $info['ANOTHER_PAGES_ID'],
                'parent_id' => $info['PARENT_ID']
            ));

            $this->domXml->create_element('name', $info['NAME']);
        }
    }
}