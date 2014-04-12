<?php

class AnnouncementController extends CommonBaseController
{

    public $Announcement;
    public $ap_id;
    public $rubrics_id;
    public $types_id;
    public $announcement_per_page;
    public $announcement_id;
    public $order = array();
    public $error = array();
    public $is_post = false;

    function init()
    {
        parent::init();

        $this->ap_id = $this->AnotherPages->getPageId('/announcement/');
        if (!$this->AnotherPages->getStatus($this->ap_id)) {
            header("HTTP/1.0 404 Not Found");
            echo "<h1>404 Not found!</h1>";
            exit;
        }

        $http = new Zend_Controller_Request_Http();

        $this->getSysText('page_main');

        Zend_Loader::loadClass('Announcement');
        $this->Announcement = new Announcement();

        $action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

        $this->getSysText('system_all');
        $this->getSysText('announcement_type');
        $this->getSysText('announcement_rubrics');
        $this->getSysText('add_announcement');

        $this->getSysText('form_email');
        $this->getSysText('form_name');
        $this->getSysText('form_captcha');
        $this->getSysText('form_refresh');
        $this->getSysText('form_phone');
        $this->getSysText('form_fax');
        $this->getSysText('form_ann_text');
        $this->getSysText('form_country');
        $this->getSysText('form_organization');
        $this->getSysText('form_city');
        $this->getSysText('form_title');

        if ($action == 'success') {

        } elseif ($action == 'view') {

            $this->getDocMeta($this->ap_id);
            if ($this->_hasParam('n'))
                $this->announcement_id = $this->_getParam('n');
        }
        else {
            $this->getDocMeta($this->ap_id);
            Zend_Controller_Front::getInstance()->getRequest()->setActionName('all');

            $this->rubrics_id = $this->_getParam('rid');
            $this->types_id = $this->_getParam('tid');

            $this->rubrics_id = empty($this->rubrics_id) ? 0 : $this->rubrics_id;
            $this->types_id = empty($this->types_id) ? 0 : $this->types_id;

            $this->getBanners('banner_announcement_form', 5, 10);

            if ($http->isPost()) {
                if ($http->has('announcement_rubrics_id') && $http->getPost('announcement_rubrics_id')) {
                    $this->order['ANNOUNCEMENT_RUBRICS_ID'] = $http->getPost('announcement_rubrics_id');
                }

                if ($http->has('announcement_types_id') && $http->getPost('announcement_types_id')) {
                    $this->order['ANNOUNCEMENT_TYPES_ID'] = $http->getPost('announcement_types_id');
                }

                if ($http->has('organization') && $http->getPost('organization')) {
                    $this->order['ORGANIZATION'] = $http->getPost('organization');
                }

                if ($http->has('country') && $http->getPost('country')) {
                    $this->order['COUNTRY'] = $http->getPost('country');
                }

                if ($http->has('city') && $http->getPost('city')) {
                    $this->order['CITY'] = $http->getPost('city');
                }

                if ($http->has('title') && $http->getPost('title')) {
                    $this->order['TITLE'] = $http->getPost('title');
                }

                if ($http->has('name') && $http->getPost('name')) {
                    $this->order['NAME'] = $http->getPost('name');
                }

                if ($http->has('phone')) {
                    $this->order['PHONE'] = $http->getPost('phone');
                }

                if ($http->has('fax')) {
                    $this->order['FAX'] = $http->getPost('fax');
                }

                if ($http->has('text')) {
                    $this->order['TEXT'] = $http->getPost('text');
                }

                if ($http->has('email') && $http->getPost('email')) {
                    $this->order['EMAIL'] = $http->getPost('email');
                }

                if (!empty($this->order['email'])) {
                    if (!preg_match("/[a-zA-Z0-9\-\_]+\@[a-zA-Z0-9\-\_]+(\.[a-zA-Z0-9]+){1,}/",
                                    $this->order['email'])) {
                        $this->error[count($this->error)] = 'Не верный формат E-mail';
                    }
                }

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

        if (empty($this->error) && $this->is_post) {
            $this->order = $this->form_processing($this->order);
            $this->order['DATE'] = date("Y-m-d H:i:s");
            $this->order['STATUS'] = 0;

            $this->Announcement->insertAnnouncement($this->order);

            $this->_redirector->gotoUrl('/announcement/success/');
        } elseif (!empty($this->error) && $this->is_post) {
            $this->viewErrors($this->error);
            $this->sendErrorData($this->order);
        }

        $o_data['ap_id'] = $this->ap_id;
        $o_data['is_vote'] = '';
        $o_data['rubrics_id'] = $this->rubrics_id;
        $o_data['types_id'] = $this->types_id;
        $this->openData($o_data);

        $this->getRubrics();
        $this->getTypes();

        $this->getWorkRubrics($this->rubrics_id);
        $this->getWorkTypes($this->types_id);

//      echo $this->domXml->getXML();
//      exit;
    }

    public function allAction()
    {

        $this->announcement_per_page = $this->getSettingValue('announcement_per_page') ? $this->getSettingValue('announcement_per_page') : 15;
        $count = $this->Announcement->getAnnouncementCount($this->types_id,
                                                           $this->types_id);

        $page = $this->_getParam('page');

        if (empty($page))
            $page = 1;

        $startSelect = ($page - 1) * $this->announcement_per_page;
        $startSelect = $startSelect > $count ? 0 : $startSelect;
        $startSelect = $startSelect < 0 ? 0 : $startSelect;

        $pcount = ceil($count / $this->announcement_per_page);

        $this->makeSectionInfo($count, $page, $pcount);

        $this->getAnnouncement($this->rubrics_id, $this->types_id, $startSelect,
                               $this->announcement_per_page);
    }

    public function viewAction()
    {
        $announcement = $this->Announcement->getAnnouncementInfo($this->announcement_id);
        if (!empty($announcement)) {
            $this->domXml->create_element('announcement', '', 2);
            $this->domXml->set_attribute(array('id' => $announcement['ANNOUNCEMENT_ID']
                , 'rubrics_id' => $announcement['ANNOUNCEMENT_RUBRICS_ID']
                , 'types_id' => $announcement['ANNOUNCEMENT_TYPES_ID']
            ));

            $this->domXml->create_element('title', $announcement['TITLE']);
            $this->domXml->create_element('organization',
                                          $announcement['ORGANIZATION']);
            $this->domXml->create_element('country', $announcement['COUNTRY']);
            $this->domXml->create_element('city', $announcement['CITY']);
            $this->domXml->create_element('name', $announcement['NAME']);
            $this->domXml->create_element('phone', $announcement['PHONE']);
            $this->domXml->create_element('fax', $announcement['FAX']);
            $this->domXml->create_element('email', $announcement['EMAIL']);
            $this->domXml->create_element('date', $announcement['ndate']);
            $this->domXml->create_element('ar_name', $announcement['AR_NAME']);
            $this->domXml->create_element('at_name', $announcement['AT_NAME']);

            $this->setXmlNode(nl2br($announcement['TEXT']), 'text');
            $this->domXml->go_to_parent();
        }
    }

    public function makeSectionInfo($count, $page, $pcount)
    {
        $this->domXml->set_tag('//page/data', true);
        $this->domXml->create_element('section', '', 2);

        $this->domXml->set_attribute(array('count' => $count
            , 'page' => $page
            , 'pcount' => $pcount
        ));

        $this->domXml->go_to_parent();
    }

    private function getDocMeta($id)
    {
        $info = $this->AnotherPages->getDocInfo($id, $this->lang_id);
        if ($info) {
            $this->domXml->set_tag('//page', true);
            $this->domXml->create_element('docinfo', '', 2);

            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
            else
                $lang = '';

            $href = $lang . '/announcement/';

            $this->domXml->create_element('url', $href);
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

    public function getRubrics()
    {
        $rubrics = $this->Announcement->getRubrics($this->lang_id);
        if (!empty($rubrics)) {
            foreach ($rubrics as $view) {
                $this->domXml->create_element('rubrics', '', 2);
                $this->domXml->set_attribute(array('id' => $view['ANNOUNCEMENT_RUBRICS_ID']));

                $this->domXml->create_element('name', $view['NAME']);

                $this->domXml->go_to_parent();
            }
        }
    }

    public function getTypes()
    {
        $types = $this->Announcement->getTypes($this->lang_id);
        if (!empty($types)) {
            foreach ($types as $view) {
                $this->domXml->create_element('types', '', 2);
                $this->domXml->set_attribute(array('id' => $view['ANNOUNCEMENT_TYPES_ID']));

                $this->domXml->create_element('name', $view['NAME']);

                $this->domXml->go_to_parent();
            }
        }
    }

    public function getWorkRubrics($id)
    {
        $rubrics = $this->Announcement->getWorkRubrics($this->types_id,
                                                       $this->lang_id);
        if (!empty($rubrics)) {
            foreach ($rubrics as $view) {
                $sel = 0;
                if ($this->rubrics_id == $view['ANNOUNCEMENT_RUBRICS_ID'])
                    $sel = 1;
                $this->domXml->create_element('work_rubrics', '', 2);
                $this->domXml->set_attribute(array('id' => $view['ANNOUNCEMENT_RUBRICS_ID']
                    , 'sel' => $sel
                ));

                $this->domXml->create_element('name', $view['NAME']);

                $this->domXml->go_to_parent();
            }
        }
    }

    public function getWorkTypes($id)
    {
        $types = $this->Announcement->getWorkTypes($this->lang_id);
        if (!empty($types)) {
            foreach ($types as $view) {
                $sel = 0;
                if ($this->types_id == $view['ANNOUNCEMENT_TYPES_ID'])
                    $sel = 1;
                $this->domXml->create_element('work_types', '', 2);
                $this->domXml->set_attribute(array('id' => $view['ANNOUNCEMENT_TYPES_ID']
                    , 'sel' => $sel
                ));

                $this->domXml->create_element('name', $view['NAME']);

                $this->domXml->go_to_parent();
            }
        }
    }

    public function getAnnouncement($rubrics_id, $types_id, $startSelect,
                                    $announcement_per_page)
    {
        $announcement = $this->Announcement->getAnnouncement($rubrics_id,
                                                             $types_id,
                                                             $startSelect,
                                                             $announcement_per_page);
        if (!empty($announcement)) {
            foreach ($announcement as $view) {
                $this->domXml->create_element('announcement', '', 2);
                $this->domXml->set_attribute(array('id' => $view['ANNOUNCEMENT_ID']
                    , 'rubrics_id' => $view['ANNOUNCEMENT_RUBRICS_ID']
                    , 'types_id' => $view['ANNOUNCEMENT_TYPES_ID']
                ));

                $this->domXml->create_element('title', $view['TITLE']);
                $this->domXml->create_element('organization',
                                              $view['ORGANIZATION']);
                $this->domXml->create_element('country', $view['COUNTRY']);
                $this->domXml->create_element('city', $view['CITY']);
                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('phone', $view['PHONE']);
                $this->domXml->create_element('fax', $view['FAX']);
                $this->domXml->create_element('email', $view['EMAIL']);
                $this->domXml->create_element('date', $view['ndate']);
                $this->domXml->create_element('ar_name', $view['AR_NAME']);
                $this->domXml->create_element('at_name', $view['AT_NAME']);

                $this->setXmlNode(nl2br($view['TEXT']), 'text');

                $this->domXml->go_to_parent();
            }
        }
    }

    function successAction()
    {
        $doc_id = $this->AnotherPages->getPageId('/announcement/success/');
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

}
