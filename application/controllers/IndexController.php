<?php
Zend_Loader::loadClass('Mailer');
class IndexController extends CommonBaseController
{
    public $http;

    function init()
    {
        parent::init();
        $this->http = new Zend_Controller_Request_Http();

        if ($this->http->getCookie('play_bool')) {
            $this->domXml->set_tag('//page', true);
            $this->domXml->set_attribute(array('play_bool' => 1));
            $this->domXml->go_to_parent();
        }

        $this->getSysText('page_main');
        $this->getSysText('arc_vote');
        $this->getSysText('all_news');
        $this->getSysText('play_againe');

        $this->getSysText('text_our_clients');
        $this->getSysText('text_index_news');

        Zend_Loader::loadClass('Clients');
    }

    public function indexAction()
    {
        $this->getDocMeta(1);

        $o_data['id'] = 0;
        $o_data['is_start'] = 1;
        $o_data['is_vote'] = 0;
        $o_data['is_start'] = 1;
        $this->openData($o_data);

        $this->domXml->set_tag('//data', true);

        $this->getHeader();
        $this->getClients();

        $this->getDocInfo(1);

        $news_index_amount = $this->getSettingValue('news_index_amount') ? $this->getSettingValue('news_index_amount'):2;
        $this->getIndexNews($news_index_amount);

        $this->domXml->set_tag('//data', true);

        /* Вывод текста стартовой */
        $this->getDocXml($this->getPageId('/'), 0, true);

        $this->domXml->set_tag('//data', true);
        $this->getBanners('index_under_news', 11, 16, 0);
    }

    public function mapsAction()
    {
        $doc = $this->AnotherPages->getDocXml($this->getPageId('/index/map/'),
                                                               0, $this->lang_id);
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

        if ($this->http->isPost()) {
            if ($this->http->has('opr') && $this->http->getPost('opr')) {
                $opr = $this->http->getPost('opr');
            }

            $vopros_id = $this->Vopros->getVoprosID($opr);
            $this->Vopros->voprosUp($vopros_id);
            $this->Vopros->otvetUp($opr);

            setcookie("sklad_vote", $vopros_id, time() + 3600 * 24 * 3, "/");

            $this->_redirector->gotoUrl('/index/vote/');
        }

        $this->befor_path[0]['name'] = 'Архив голосований';
        $this->befor_path[0]['url'] = '';

        $this->getDocPath($ap_id);

        $this->getAllVote();
    }

    public function clientvoteAction()
    {
        $client = $this->_getParam('client');
        if ($this->http->isPost()) {
            $this->Vopros->setClientVopros($client);

            $this->_redirector->gotoUrl('/index/votesuccess/');
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
            $this->domXml->set_attribute(array('another_pages_id' => $info['ANOTHER_PAGES_ID']
                , 'parent_id' => $info['PARENT_ID']
            ));

            $this->domXml->create_element('name', $info['NAME']);
        }
    }

    public function callbackAction()
    {

        if ($this->http->isPost()) {
            $data['NAME'] = $this->http->getPost('name');
            $data['PHONE'] = $this->http->getPost('phone');
            $data['CALLBACK_TIME_ID'] = $this->http->getPost('callback_time_id');
            $data['DESCRIPTION'] = $this->http->getPost('description');

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

            $time = $this->AnotherPages->getCallbackTimeName($data['CALLBACK_TIME_ID']);

            $doc_id = $this->AnotherPages->getPageId('/callback/');
            $letter_xml = $this->AnotherPages->getDocXml($doc_id, 0,
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
                            Mailer::send($params);
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

            $doc_id = $this->AnotherPages->getPageId('/complain/');
            $letter_xml = $this->AnotherPages->getDocXml($doc_id, 0, $this->lang_id);

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
                            Mailer::send($params);
                        }
                    }
                }
            }

            $this->AnotherPages->insertData('COMPLAIN', $data);
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

    public function getDocMeta($ap_id)
    {
        $info = $this->AnotherPages->getDocInfo($ap_id, $this->lang_id);
        if ($info) {

            $this->domXml->create_element('docinfo', '', 2);
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

    private function getIndexNews($news_index_amount)
    {

        $news = $this->News->getNewsIndex(0, $this->lang_id, $news_index_amount);
        if (!empty($news)) {
            if ($this->lang_id > 0) {
                $lang = '/' . $this->lang;
            } else {
                $lang = '';
            }

            foreach ($news as $n_view) {
                $url = '';
                $is_url = 0;

                if (!empty($n_view['URL']) && strpos($n_view['URL'], 'http://') !== false) {
                    $is_lang = true;
                    $href = $n_view['URL'];
                } elseif (!empty($n_view['URL'])) {
                    $href = $n_view['URL'];
                    $is_url = 1;
                } else {
                    $is_lang = true;
                    $href = '/news/view/n/' . $n_view['NEWS_ID'] . '/';
                }

                $_href = $this->AnotherPages->getSefURLbyOldURL($href);

                if (!empty($_href) && $is_lang) {
                    $href = $lang . $_href;
                } elseif (!empty($_href) && !$is_lang) {
                    $href = $_href;
                }

                $this->domXml->create_element('news', '', 2);
                $this->domXml->set_attribute(array('id' => $n_view['NEWS_ID']
                                                 , 'is_url' => $is_url
                ));

                $this->domXml->create_element('name', $n_view['NAME']);
                $this->domXml->create_element('date', $n_view['date']);
                $this->domXml->create_element('url', $href);

                if (!empty($n_view['IMAGE1']) && strchr($n_view['IMAGE1'], "#")) {
                    $tmp = explode('#', $n_view['IMAGE1']);
                    $this->domXml->create_element('image1', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0],
                        'w' => $tmp[1],
                        'h' => $tmp[2]
                            )
                    );
                    $this->domXml->go_to_parent();
                }

                $this->setXmlNode($n_view['descript'], 'descript');
                $this->domXml->go_to_parent();
            }
        }

    }

    /**
     * Метод для получения информации о странице
     * @access   public
     * @param    integer $id
     * @return   string xml
     */
    public function getDocInfo($ap_id)
    {
        $info = $this->AnotherPages->getDocInfo($ap_id, $this->lang_id);
        if ($info) {
            $this->domXml->set_tag('//data', true);

            $this->domXml->create_element('docinfo', '', 2);
            $this->domXml->set_attribute(array('another_pages_id' => $info['ANOTHER_PAGES_ID']
                , 'parent_id' => $info['PARENT_ID']
                    )
            );

            $this->domXml->create_element('name', $info['NAME']);

            $this->getDocXml(1, 0, true, $this->lang_id);
            $this->domXml->go_to_parent();

            $this->domXml->create_element('sectioninfo', '', 2);

            $image = $this->AnotherPages->getSectionImage($ap_id);

            if (!empty($image) && strchr($image, "#")) {
                $tmp = explode('#', $image);
                $this->domXml->create_element('image', '', 2);
                $this->domXml->set_attribute(array('src' => '/images/pg/'.$tmp[0]
                    , 'w' => $tmp[1]
                    , 'h' => $tmp[2]
                ));
                $this->domXml->go_to_parent();
            }

            $this->domXml->go_to_parent();
        }
    }

    private function getHeader()
    {
        $headers = $this->AnotherPages->getHeader($this->lang_id);
        if (!empty($headers)) {
            foreach ($headers as $val) {
                $this->domXml->create_element('headers', '', 2);

                $this->domXml->create_element('url', $val['URL']);

                $this->setXmlNode($val['DESCRIPTION'], 'description');
                if (!empty($val['IMAGE']) && strchr($val['IMAGE'], "#")) {
                    $tmp = explode('#', $val['IMAGE']);
                    $this->domXml->create_element('image', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0]
                        , 'w' => $tmp[1]
                        , 'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }

                if (!empty($val['IMAGE1']) && strchr($val['IMAGE1'], "#")) {
                    $tmp = explode('#', $val['IMAGE1']);
                    $this->domXml->create_element('image_alt_text', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0]
                        , 'w' => $tmp[1]
                        , 'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }

                $this->domXml->go_to_parent();
            }
        }
    }

    private function getClients()
    {
        $Clients = new Clients();
        $clients = $Clients->getClientsIndex($this->lang_id);
        if ($clients) {
            $i_li = 0;
            $i_td = 0;
            foreach ($clients as $view) {
                if (($i_td > 0) && ($i_td % 3) == 0) {
                    $this->domXml->go_to_parent();
                }

                if (($i_td > 0) && ($i_td % 12) == 0) {
                    $this->domXml->go_to_parent();
                }

                if (($i_td % 12) == 0) {
                    $this->domXml->create_element('clients_li', '', 2);
                }

                if (($i_td % 3) == 0) {
                    $this->domXml->create_element('clients_td', '', 2);
                }

                $this->domXml->create_element('clients', '', 2);
                $this->domXml->set_attribute(array('client_id' => $view['CLIENT_ID']));

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('email', $view['EMAIL']);
                $this->domXml->create_element('url', $view['URL']);
                $this->domXml->create_element('description',
                                              $view['DESCRIPTION']);

                if (!empty($view['IMAGE1']) && strchr($view['IMAGE1'], "#")) {
                    $tmp = explode('#', $view['IMAGE1']);
                    $this->domXml->create_element('image', '', 2);
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

    public function caphainpAction()
    {
        $caphainp = '';
        if ($this->http->isGet()) {
            if ($this->http->has('captcha'))
                $caphainp = $this->http->getQuery('captcha');
        }

        if ($caphainp == $_SESSION['biz_captcha'])
            echo 'true';
        else
            echo 'false';

        exit;
    }

    public function testAction()
    {
        var_dump($_FILES);
        exit;
    }

}

?>
