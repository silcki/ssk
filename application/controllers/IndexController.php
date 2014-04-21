<?php
class IndexController extends Core_Controller_Action_Abstract
{
    /**
     * @var AnotherPages
     */
    private $_anotherPages;
    
    public function init()
    {
        parent::init();

        if ($this->requestHttp->get('play_bool')) {
            $this->domXml->set_tag('//page', true);
            $this->domXml->set_attribute(array('play_bool' => 1));
            $this->domXml->go_to_parent();
        }

        $this->_anotherPages = $this->getServiceManager()->getModel()->getAnotherPages();
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

        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getDocMeta(1);

        $o_data['id'] = 0;
        $o_data['is_start'] = 1;
        $o_data['is_vote'] = 0;
        $o_data['is_start'] = 1;
        $this->openData($o_data);

        $this->domXml->set_tag('//data', true);
        $this->getServiceManager()->getHelper()->getAnotherPages()
            ->setParams($params)
            ->getHeader()
            ->getDocInfo(1)
            ->getDocXml($this->getPageId('/'), 0, true);

        $this->getServiceManager()->getHelper()->getClients()
            ->setParams($params)
            ->getClients();

        $this->getDocInfo(1);

        $news_index_amount = $this->getSettingValue('news_index_amount') ? $this->getSettingValue('news_index_amount'):2;

        $this->getServiceManager()->getHelper()->getNews()
            ->setParams($params)
            ->getIndexNews($news_index_amount);
    }

    public function mapsAction()
    {
        $doc = $this->AnotherPages->getDocXml($this->getPageId('/index/map/'), 0, $this->lang_id);
        echo stripslashes($doc);
        exit;
    }

    public function voteAction()
    {
        $ap_id = $this->AnotherPages->getPageId('/index/vote/');

        $this->getDocMeta($ap_id);

        $o_data['id'] = 0;
        $o_data['is_vote'] = 1;
        $this->openData($o_data);

        $this->getDocInfo($ap_id);

        if ($this->requestHttp->isPost()) {
            $opr = $this->requestHttp->getPost('opr', null);

            $vopros_id = $this->Vopros->getVoprosID($opr);
            $this->Vopros->voprosUp($vopros_id);
            $this->Vopros->otvetUp($opr);

            setcookie("sklad_vote", $vopros_id, time() + 3600 * 24 * 3, "/");

            $this->redirect('/index/vote/');
        }

        $this->befor_path[0]['name'] = 'Архив голосований';
        $this->befor_path[0]['url'] = '';

        $this->getDocPath($ap_id);

        $this->getAllVote();
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

    function votesuccessAction()
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

    public function callbackAction()
    {

        if ($this->requestHttp->isPost()) {
            $data['NAME'] = $this->requestHttp->getPost('name');
            $data['PHONE'] = $this->requestHttp->getPost('phone');
            $data['CALLBACK_TIME_ID'] = $this->requestHttp->getPost('callback_time_id');
            $data['DESCRIPTION'] = $this->requestHttp->getPost('description');

            if (empty($data['NAME'])) {
                $err = $this->Textes->getSysText('text_callback_errore_name');
                echo $err['DESCRIPTION'];
                exit;
            }

            if (empty($data['PHONE'])) {
                $err = $this->Textes->getSysText('text_callback_errore_phone');
                echo $err['DESCRIPTION'];
                exit;
            }

            if (empty($data['CALLBACK_TIME_ID'])) {
                $err = $this->Textes->getSysText('text_callback_errore_callback_time');
                echo $err['DESCRIPTION'];
                exit;
            }

            $time = $this->_anotherPages->getCallbackTimeName($data['CALLBACK_TIME_ID']);

            $doc_id = $this->_anotherPages->getPageId('/callback/');
            $letter_xml = $this->_anotherPages->getDocXml($doc_id, 0,
                                                         $this->lang_id);

            $message_admin = $letter_xml;

            if (!empty($data['NAME']))
                $message_admin = str_replace("##name##", $data['NAME'],
                                             $message_admin);
            else
                $message_admin = str_replace("##name##", '', $message_admin);

            if (!empty($data['PHONE']))
                $message_admin = str_replace("##phone##", $data['PHONE'],
                                             $message_admin);
            else
                $message_admin = str_replace("##phone##", '', $message_admin);

            if (!empty($time))
                $message_admin = str_replace("##time##", $time, $message_admin);
            else
                $message_admin = str_replace("##time##", '', $message_admin);

            if (!empty($data['DESCRIPTION']))
                $message_admin = str_replace("##description##",
                                             $data['DESCRIPTION'],
                                             $message_admin);
            else
                $message_admin = str_replace("##description##", '',
                                             $message_admin);

            $message_admin = '<html><head><meta  http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head><body>'
                    . $message_admin . '</body></html>';

            $subject = $this->Textes->getSysText('callback_subject', $this->lang_id);

            $to = $this->getSettingValue('callback_email');
            if ($to) {
                $email_from = $this->getSettingValue('email_from');
                $patrern = '/(.*)<?([a-zA-Z0-9\-\_]+\@[a-zA-Z0-9\-\_]+(\.[a-zA-Z0-9]+?)+?)>?/U';
                preg_match($patrern, $email_from, $arr);

                $params['mailerFrom'] = empty($arr[2]) ? '' : trim($arr[2]);
                $params['mailerFromName'] = empty($arr[1]) ? '' : trim($arr[1]);

                $params = array_merge($params, $this->getMailTrasportData());

                $manager_emails_arr = explode(";", $to);
                if (!empty($manager_emails_arr)) {
                    $params['message'] = $message_admin;
                    $params['subject'] = $subject['DESCRIPTION'];

                    foreach ($manager_emails_arr as $mm) {
                        $mm = trim($mm);
                        if (!empty($mm)) {
                            $params['to'] = $mm;
                            Core_Controller_Action_Helper_Mailer::send($params);
                        }
                    }
                }
            }

            $this->AnotherPages->insertData('CALLBACK', $data);
            echo 1;
            exit;
        }

        $callback_time = $this->AnotherPages->getCallbackTime($this->lang_id);
        if (!empty($callback_time)) {
            foreach ($callback_time as $view) {
                $this->domXml->create_element('callback_time', '', 2);
                $this->domXml->set_attribute(array('id' => $view['CALLBACK_TIME_ID']
                ));

                $this->domXml->create_element('name', $view['NAME']);

                $this->domXml->go_to_parent();
            }
        }
    }

    public function complainAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data['NAME'] = $request->getPost('name');
            $data['PHONE'] = $request->getPost('phone');
            $data['EMAIL'] = $request->getPost('email');
            $data['DESCRIPTION'] = $request->getPost('description');

            if (empty($data['NAME'])) {
                $err = $this->Textes->getSysText('text_complain_errore_name');
                echo $err['DESCRIPTION'];
                exit;
            }

            if (empty($data['DESCRIPTION'])) {
                $err = $this->Textes->getSysText('text_complain_errore_description');
                echo $err['DESCRIPTION'];
                exit;
            }

            $doc_id = $this->_anotherPages->getPageId('/complain/');
            $letter_xml = $this->_anotherPages->getDocXml($doc_id, 0, $this->lang_id);

            $message_admin = $letter_xml;

            if (!empty($data['NAME'])) {
                $message_admin = str_replace("##name##", $data['NAME'], $message_admin);
            } else {
                $message_admin = str_replace("##name##", '', $message_admin);
            }

            if (!empty($data['PHONE'])) {
                $message_admin = str_replace("##phone##", $data['PHONE'], $message_admin);
            } else {
                $message_admin = str_replace("##phone##", '', $message_admin);
            }

            if (!empty($data['EMAIL'])) {
                $message_admin = str_replace("##email##", $data['EMAIL'], $message_admin);
            } else {
                $message_admin = str_replace("##email##", '', $message_admin);
            }

            if (!empty($data['DESCRIPTION'])) {
                $message_admin = str_replace("##description##", $data['DESCRIPTION'], $message_admin);
            } else {
                $message_admin = str_replace("##description##", '', $message_admin);
            }

            $message_admin = '<html><head><meta  http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head><body>'
                . $message_admin . '</body></html>';

            $subject = $this->Textes->getSysText('complain_subject', $this->lang_id);

            $to = $this->getSettingValue('complain_email');
            if ($to) {
                $email_from = $this->getSettingValue('email_from');
                $patrern = '/(.*)<?([a-zA-Z0-9\-\_]+\@[a-zA-Z0-9\-\_]+(\.[a-zA-Z0-9]+?)+?)>?/U';
                preg_match($patrern, $email_from, $arr);

                $params['mailerFrom'] = empty($arr[2]) ? '' : trim($arr[2]);
                $params['mailerFromName'] = empty($arr[1]) ? '' : trim($arr[1]);

                $params = array_merge($params, $this->getMailTrasportData());

                $manager_emails_arr = explode(";", $to);
                if (!empty($manager_emails_arr)) {
                    $params['message'] = $message_admin;
                    $params['subject'] = $subject['DESCRIPTION'];

                    foreach ($manager_emails_arr as $mm) {
                        $mm = trim($mm);
                        if (!empty($mm)) {
                            $params['to'] = $mm;
                            Core_Controller_Action_Helper_Mailer::send($params);
                        }
                    }
                }
            }

            $this->_anotherPages->insertData('COMPLAIN', $data);
            echo 1;
            exit;
        }
    }

    private function getAllVote()
    {
        $all_vopros = $this->Vopros->getAllVopros($this->lang_id);
        if (!empty($all_vopros)) {
            $this->domXml->set_tag('//data', true);
            foreach ($all_vopros as $view) {
                $this->domXml->create_element('resultvopros', '', 2);
                $this->domXml->set_attribute(array('id' => $view['VOPROS_ID']
                    , 'count' => $view['COUNT_']
                ));

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('data_start', $view['data_start_result']);
                $this->domXml->create_element('data_stop', $view['data_stop_result']);

                $otvets = $this->Vopros->getOtvets($view['VOPROS_ID'],
                                                   $this->lang_id);
                $max_otvet_id = $this->Vopros->getMaxOtvet($view['VOPROS_ID'],
                                                           $this->lang_id);
                if (!empty($otvets)) {
                    foreach ($otvets as $otvetsview) {
                        $this->domXml->create_element('resultotvets', '', 2);

                        if ($view['COUNT_'] > 0)
                            $percent = round(($otvetsview['COUNT_'] * 100) / $view['COUNT_'],
                                             2);
                        else
                            $percent = 0;

                        if ($max_otvet_id == $otvetsview['COUNT_'])
                            $is_max = 1;
                        else
                            $is_max = 0;

                        $this->domXml->set_attribute(array('id' => $otvetsview['OTVETS_ID']
                            , 'percent' => $percent
                            , 'count' => $otvetsview['COUNT_']
                            , 'is_max' => $is_max
                        ));

                        $this->domXml->create_element('name', $otvetsview['NAME']);

                        $this->domXml->go_to_parent();
                    }
                }
                $this->domXml->go_to_parent();
            }
        }
    }

    public function caphainpAction()
    {
        $caphainp = $this->requestHttp->getQuery('captcha', null);
        if ($caphainp == $_SESSION['biz_captcha']) {
            echo 'true';
        } else {
            echo 'false';
        }

        exit;
    }
}