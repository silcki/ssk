<?php

class CatController extends CommonBaseController
{
    private $item_id;
    private $childs;

    public $catalog_id;
    public $mail;
    public $order = array();
    public $error = array();
    public $is_post = false;

    public function init()
    {
        parent::init();

        Zend_Loader::loadClass('Article');
        $this->Article = new Article();

        $this->getSysText('page_main');
        $this->getSysText('item_catalog');
        $this->getSysText('text_subject_articles');
        $this->getSysText('all_articles');
        $this->getSysText('form_button_send');
        $this->getSysText('text_item_photo');

        if ($this->work_action == 'view') {
            $this->catalog_id = $this->_getParam('n');
        } elseif ($this->work_action == 'success') {

        } elseif ($this->work_action == 'item') {
            $banner_attach_file = $this->SectionAlign->getBanner(7, 12, $this->lang_id);
            if (!empty($banner_attach_file)) {
                $banner_attach_file['DESCRIPTION'] = str_replace("##size##", $this->max_post_str(), $banner_attach_file['DESCRIPTION']);
                $this->getBannerFromVar('banner_attach_file', $banner_attach_file);
            }

            $this->getSysText('zakaz_stellag');
            $this->getSysText('form_email');
            $this->getSysText('form_name');
            $this->getSysText('form_captcha');
            $this->getSysText('form_refresh');
            $this->getSysText('form_phone');
            $this->getSysText('form_lastname');
            $this->getSysText('item_text');
            $this->getSysText('form_city');
            $this->getSysText('form_description');
            $this->getSysText('feed_attach');
            $this->getSysText('form_company');
            $this->getSysText('form_fields_error');
            $this->getSysText('form_back_to_image');

            $this->getBanners('banner_item_form', 4, 9);

            $this->catalog_id = $this->_getParam('n', 0);
            $this->item_id = $this->_getParam('it', 0);

            $children_item_count = $this->Catalogue->getItemsCount($this->catalog_id);

            if ($children_item_count > 1) {
                $this->after_path[0]['name'] = $this->Catalogue->getItemName($this->item_id, $this->lang_id);
                $this->after_path[0]['url'] = '';
            }
        } else {
            Zend_Controller_Front::getInstance()->getRequest()->setActionName('all');
        }

        if ($this->work_action == 'all' || $this->work_action == 'success')
            $this->catalog_id = 0;
        $res = $this->Catalogue->getParents($this->catalog_id);
        if ($this->catalog_id === false || ($this->catalog_id > 0 && empty($res))) {
            $this->page_404();
        }

        $this->childs = $this->Catalogue->getChildren($this->catalog_id);

        $o_data['cat_id'] = $this->catalog_id;
        $o_data['item_id'] = $this->item_id;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        if (!empty($this->item_id)) {
            $this->getItemMeta();
        } elseif (!empty($this->catalog_id)) {
            $this->getCatInfo();
        } else {
            $this->getCatAllInfo();
        }

//        $ap_id = $this->AnotherPages->getPageId('/cat/all/');
//        $this->getDocInfo($ap_id);

        $this->getPath($this->catalog_id);

        if (!empty($this->catalog_id)) {
            $this->getCatalogArticle();
        }
    }

    private function validateSend()
    {
        $http = new Zend_Controller_Request_Http();
        if (!$http->isPost())
            return false;

        $caphainp = $http->getPost('captcha');
        if ($caphainp != $_SESSION['biz_captcha']) {
            return false;
        }

        $this->order['NAME'] = $http->getPost('name');
        $this->order['SURNAME'] = $http->getPost('surname');
        $this->order['TELMOB'] = $http->getPost('telmob');
        $this->order['COMPANY'] = $http->getPost('company');
        $this->order['DESCRIPTION'] = $http->getPost('description');
        $this->order['CITY'] = $http->getPost('city');
        $this->order['EMAIL'] = $http->getPost('email');

        return true;
    }

    public function allAction()
    {
        $this->getSubCats($this->catalog_id);
    }

    public function viewAction()
    {
        Zend_Loader::loadClass('CatHelper');
        $params['lang_id'] = $this->lang_id;
        $params['lang'] = $this->lang;

        $catHelper = new CatHelper($params);
        $domXml = $catHelper->getItems($this->catalog_id);

        $this->domXml->set_tag('//data', true);
        $this->domXml->appendXML($domXml->getXMLobject());
    }

    public function itemAction()
    {
        $this->getItem($this->item_id);

        $this->domXml->set_tag('//data', true);
        $this->domXml->create_element('sectioninfo', '', 2);
        $image = $this->Catalogue->getSectionImage($this->catalog_id);
        if (!empty($image) && strchr($image, "#")) {
            $tmp = explode('#', $image);
            $this->domXml->create_element('image', '', 2);
            $this->domXml->set_attribute(array('src' => '/images/cat/'.$tmp[0]
            , 'w' => $tmp[1]
            , 'h' => $tmp[2]
            ));
            $this->domXml->go_to_parent();
        }
    }

    public function getCatAllInfo()
    {
        $docId = $this->AnotherPages->getPageByURL('/cat/all/');
        if (!empty($docId)) {
            $docInfo = $this->AnotherPages->getDocInfo($docId, $this->lang);

            $this->domXml->set_tag('//data', true);

            $this->befor_path[0]['name'] = $docInfo['NAME'];
            $this->befor_path[0]['url'] = 'cat/all/';

            $this->domXml->create_element('docinfo', '', 2);

            $this->domXml->create_element('name', $docInfo['NAME']);

            $this->domXml->create_element('title', $docInfo['TITLE']);
            $this->domXml->create_element('description', $docInfo['DESCRIPTION']);
            $this->domXml->create_element('keywords', $docInfo['KEYWORDS']);

            $this->domXml->go_to_parent();

            $this->domXml->set_tag('//data', true);
            $this->domXml->create_element('sectioninfo', '', 2);
            $image = $this->AnotherPages->getSectionImage($docId);

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

            $this->domXml->set_tag('//data', true);
            $this->getDocXml($docId, 0, true);
        }
    }
    
    public function getItemMeta()
    {
        $cat_all_name = $this->Textes->getSysText('item_catalog', $this->lang_id);
        $href = '/cat/all/';
        $_href = $this->AnotherPages->getSefURLbyOldURL($href);
        $href = !empty($_href) ? $_href:$href;
        $this->befor_path[0]['name'] = $cat_all_name['DESCRIPTION'];
        $this->befor_path[0]['url'] = $href;

        $data = $this->Catalogue->getItemMeta($this->item_id, $this->lang_id);
        if (!empty($data)) {
            $this->domXml->create_element('docinfo', '', 2);
            $this->domXml->create_element('title', $data['HTML_TITLE']);

            $descript = preg_replace("/\"([^\"]*)\"/","&#171;\\1&#187;",$data['HTML_KEYWORDS']);
            $descript = preg_replace("/\"/","&#171;",$descript);
            $this->domXml->create_element('description',$descript);

            $keyword = preg_replace("/\"([^\"]*)\"/","&#171;\\1&#187;",$data['HTML_DESCRIPTION']);
            $keyword = preg_replace("/\"/","&#171;",$keyword);
            $this->domXml->create_element('keywords',$keyword);

            $this->domXml->go_to_parent();
        }
    }

    public function getCatInfo()
    {
        $catinfo = $this->Catalogue->getCatInfo($this->catalog_id, $this->lang_id);
        $cat_counts = $this->Catalogue->getChildrenCount($this->catalog_id);
        $item_counts = $this->Catalogue->getItemsCount($this->catalog_id);

        $cat_all_name = $this->Textes->getSysText('item_catalog', $this->lang_id);
        $href = '/cat/all/';
        $_href = $this->AnotherPages->getSefURLbyOldURL($href);
        $href = !empty($_href) ? $_href:$href;
        $this->befor_path[0]['name'] = $cat_all_name['DESCRIPTION'];
        $this->befor_path[0]['url'] = $href;

        $count = ($item_counts > 0) ? $item_counts : $cat_counts;

        $this->domXml->set_tag('//data', true);
        $this->domXml->create_element('catinfo', '', 2);
        $this->domXml->set_attribute(array('catalogue_id' => $catinfo['CATALOGUE_ID']
            , 'cat_counts' => $count
        ));

        $this->domXml->create_element('name', $catinfo['NAME']);
        $this->domXml->create_element('catname', $catinfo['CATNAME']);
        $this->domXml->create_element('realcatname', $catinfo['REALCATNAME']);
        $this->domXml->create_element('url', $catinfo['URL']);
        $this->domXml->go_to_parent();


        $this->domXml->create_element('docinfo', '', 2);
        $this->domXml->create_element('title', $catinfo['NAME'] . ' ' . $catinfo['TITLE']);

        $descript = preg_replace("/\"([^\"]*)\"/","&#171;\\1&#187;",$catinfo['HTML_DESCRIPTION']);
        $descript = preg_replace("/\"/","&#171;",$descript);
        $this->domXml->create_element('description',$descript);

        $keyword = preg_replace("/\"([^\"]*)\"/","&#171;\\1&#187;",$catinfo['HTML_KEYWORDS']);
        $keyword = preg_replace("/\"/","&#171;",$keyword);
        $this->domXml->create_element('keywords',$keyword);
        $this->domXml->go_to_parent();

        $this->domXml->set_tag('//data', true);
        $this->domXml->create_element('sectioninfo', '', 2);
        $image = $this->Catalogue->getSectionImage($this->catalog_id);
        if (!empty($image) && strchr($image, "#")) {
            $tmp = explode('#', $image);
            $this->domXml->create_element('image', '', 2);
            $this->domXml->set_attribute(array('src' => '/images/cat/'.$tmp[0]
            , 'w' => $tmp[1]
            , 'h' => $tmp[2]
            ));
            $this->domXml->go_to_parent();
        }
        $this->domXml->go_to_parent();
    }

    private function getSubCats($id)
    {
        $this->getDocXml($id, 2, true, $this->lang_id);
        $childs = $this->Catalogue->getChildren($id);
        if (!empty($childs)) {
            if ($this->lang_id > 0) {
                $lang = '/' . $this->lang;
            } else {
                $lang = '';
            }

            foreach ($childs as $cat) {
                $data = $this->Catalogue->getCatInfo($cat, $this->lang_id);
                if (!empty($data)) {
                    $this->domXml->create_element('catalogue', '', 2);
                    $this->domXml->set_attribute(array('catalogue_id' => $data['CATALOGUE_ID']
                        , 'parent_id' => $data['PARENT_ID']
                    ));

                    $children_item_count = $this->Catalogue->getItemsCount($data['CATALOGUE_ID']);

                    $is_lang = false;
                    if (($data['ITEM_IS_DESCR'] == 1) && ($children_item_count == 1)) {
                        $item_id = $this->Catalogue->getCatFirstItems($data['CATALOGUE_ID']);
                        $href = '/cat/item/n/' . $data['CATALOGUE_ID'] . '/it/' . $item_id . '/';
                        $is_lang = true;
                    } elseif ($children_item_count > 0) {
                        $href = '/cat/view/n/' . $data['CATALOGUE_ID'] . '/';
                        $is_lang = true;
                    } else {
                        if (!empty($data['URL']) && strpos($data['URL'],
                                                           'http://') !== false) {
                            $is_lang = true;
                            $href = $data['URL'];
                        } elseif (!empty($data['URL'])) {
                            $href = $data['URL'];
                        } elseif (!empty($data['REALCATNAME']) && $data['REALCATNAME'] != '/') {
                            $href = '/cat' . $data['REALCATNAME'];
                            $is_lang = true;
                        } else {
                            $href = '/cat/' . $data['CATALOGUE_ID'] . '/';
                            $is_lang = true;
                        }
                    }

                    $_href = $this->AnotherPages->getSefURLbyOldURL($href);

                    if (!empty($_href) && $is_lang) {
                        $href = $lang . $_href;
                    } elseif (!empty($_href) && !$is_lang) {
                        $href = $_href;
                    }

                    $this->domXml->create_element('name', $data['NAME']);
                    $this->domXml->create_element('catname', $data['CATNAME']);
                    $this->domXml->create_element('realcatname', $data['REALCATNAME']);
                    $this->domXml->create_element('style', $data['COLOR_STYLE']);
                    $this->domXml->create_element('description', $data['DESCRIPTION']);
                    $this->domXml->create_element('url', $href);

//            if($data['PARENT_ID']==0)
//              $image = $this->Catalogue->getFrontImage($data['CATALOGUE_ID']);
//            else
                    $image = $data['IMAGE1'];

                    if (!empty($image) && strchr($image, "#")) {
                        $tmp = explode('#', $image);
                        $this->domXml->create_element('image1', '', 2);
                        $this->domXml->set_attribute(array('src' => $tmp[0],
                            'w' => $tmp[1],
                            'h' => $tmp[2]
                        ));
                        $this->domXml->go_to_parent();
                    }

                    $subcats = $this->Catalogue->getChildren($data['CATALOGUE_ID']);
                    if (!empty($subcats)) {
                        $this->getSubCats($data['CATALOGUE_ID']);
                    }

                    $this->domXml->go_to_parent();
                }
            }
        }
    }

    private function getItems($id)
    {
        $this->getDocXml($id, 2, true, $this->lang_id);
        $data = $this->Catalogue->getItems($id, $this->lang_id);
        if (!empty($data)) {
            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
            else
                $lang = '';

            foreach ($data as $item) {
                $this->domXml->create_element('items', '', 2);
                $this->domXml->set_attribute(array('catalogue_id' => $id
                    , 'item_id' => $item['ITEM_ID']
                        )
                );

                $href = $lang . '/cat/item/n/' . $id . '/it/' . $item['ITEM_ID'] . '/';

                $_href = $this->AnotherPages->getSefURLbyOldURL($href);
                if (!empty($_href))
                    $href = $lang . $_href;

                $this->domXml->create_element('name', $item['NAME']);
                $this->domXml->create_element('description', nl2br($item['DESCRIPTION']));
                $this->domXml->create_element('url', $href);

                if (!empty($item['IMAGE']) && strchr($item['IMAGE'], "#")) {
                    $tmp = explode('#', $item['IMAGE']);
                    $this->domXml->create_element('image', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0],
                        'w' => $tmp[1],
                        'h' => $tmp[2]
                            )
                    );
                    $this->domXml->go_to_parent();
                }


                $this->domXml->go_to_parent();
            }
        }
    }

    private function getItem($id)
    {
        $data = $this->Catalogue->getItemInfo($id, $this->lang_id);

        if ($this->validateSend()) {
            $this->order = $this->form_processing($this->order);
            $this->order['DATA'] = date("Y-m-d H:i:s");

            $this->sendMailToUser($data);
            $this->sendMailToAdmin($data);

            $this->domXml->create_element('was_send', 1, 2);
            $this->domXml->go_to_parent();

            exit;
        }

        if (!empty($data)) {
            $children_item_count = $this->Catalogue->getItemsCount($data['CATALOGUE_ID']);

            $item_is_descr = 0;
            if (($data['ITEM_IS_DESCR'] == 1) && ($children_item_count == 1)) {
                $item_is_descr = 1;
            }

            $this->domXml->create_element('item', '', 2);
            $this->domXml->set_attribute(array('item_id' => $id
                , 'catalogue_id' => $data['CATALOGUE_ID']
                , 'is_form' => $data['IS_FORM']
                , 'item_is_descr' => $item_is_descr
                    )
            );

            $this->domXml->create_element('name', $data['NAME']);
            $this->setXmlNode('<txt>' . $data['UNDER_IMAGE_TEXT'] . '</txt>',
                              'under_image_text');
            $this->setXmlNode('<txt>' . $data['POP_IMAGE_TEXT'] . '</txt>',
                              'pop_image_text');

            $this->domXml->create_element('code_map_area',
                                          $data['CODE_MAP_AREA']);

            $this->getDocXml($id, 3, true, $this->lang_id);

            $elements = $this->Catalogue->getItemElementsInfo($id, $this->lang_id);
            if (!empty($elements)) {
                foreach ($elements as $view) {
                    $this->domXml->create_element('elements', '', 2);
                    $this->domXml->set_attribute(array('id' => $view['ITEM_ELEMENTS_ID']
                        , 'name_num' => $view['NAME_NUM']
                    ));

                    $this->domXml->create_element('name', $view['NAME']);
                    $this->domXml->create_element('description',
                                                  $view['DESCRIPTION']);

                    if (!empty($view['IMAGE1']) && strchr($view['IMAGE1'], "#")) {
                        $tmp = explode('#', $view['IMAGE1']);
                        $this->domXml->create_element('image1', '', 2);
                        $this->domXml->set_attribute(array('src' => $tmp[0],
                            'w' => $tmp[1],
                            'h' => $tmp[2]
                                )
                        );
                        $this->domXml->go_to_parent();
                    }

                    $this->domXml->go_to_parent();
                }
            }

            if (!empty($data['IMAGE1']) && strchr($data['IMAGE1'], "#")) {
                $tmp = explode('#', $data['IMAGE1']);
                $this->domXml->create_element('image1', '', 2);
                $this->domXml->set_attribute(array('src' => $tmp[0],
                    'w' => $tmp[1],
                    'h' => $tmp[2]
                        )
                );
                $this->domXml->go_to_parent();
            }

            if (!empty($data['IMAGE2']) && strchr($data['IMAGE2'], "#")) {
                $tmp = explode('#', $data['IMAGE2']);
                $this->domXml->create_element('image2', '', 2);
                $this->domXml->set_attribute(array('src' => $tmp[0],
                    'w' => $tmp[1],
                    'h' => $tmp[2]
                        )
                );
                $this->domXml->go_to_parent();
            }

            $item_fotos = $this->Catalogue->getItemFotos($id, $this->lang_id);
            if (!empty($item_fotos)) {
                foreach ($item_fotos as $view) {
                    $this->domXml->create_element('item_photos', '', 2);

                    $this->domXml->create_element('name', $view['NAME']);
                    $this->setXmlNode($view['DESCRIPTION'], 'description');

                    if (!empty($view['IMAGE1']) && strchr($view['IMAGE1'], "#")) {
                        $tmp = explode('#', $view['IMAGE1']);
                        $this->domXml->create_element('image1', '', 2);
                        $this->domXml->set_attribute(array('src' => $tmp[0],
                            'w' => $tmp[1],
                            'h' => $tmp[2]
                                )
                        );
                        $this->domXml->go_to_parent();
                    }

                    if (!empty($view['IMAGE2']) && strchr($view['IMAGE2'], "#")) {
                        $tmp = explode('#', $view['IMAGE2']);
                        $this->domXml->create_element('image2', '', 2);
                        $this->domXml->set_attribute(array('src' => $tmp[0],
                            'w' => $tmp[1],
                            'h' => $tmp[2]
                                )
                        );
                        $this->domXml->go_to_parent();
                    }

                    $this->domXml->go_to_parent();
                }
            }

            $this->domXml->go_to_parent();
        }
    }

    private function sendMailToUser($item)
    {
        $zakaz_id = $this->Catalogue->insertZakaz($this->order);
        if (!empty($this->item_id)) {
            $zakaz_item = array();

            $zakaz_item['ZAKAZ_ID'] = $zakaz_id;
            $zakaz_item['CATALOGUE_ID'] = $item['CATALOGUE_ID'];
            $zakaz_item['NAME'] = $item['NAME'];
            $zakaz_item['ITEM_ID'] = $this->item_id;
            $this->Catalogue->insertOrder($zakaz_item);
        }

        $attach = '';

        $table = "<table cellspacing='0' cellpadding='2' border='1'>
              <tbody>
                  <tr>
                      <th>Наименование товара</th>
                  </tr>
                  <tr>
                      <th>" . $item['NAME'] . "</th>
                   </tr>
                 </tbody>
                </table>";

        $to = $this->order['EMAIL'];
        $subject = 'Оформление заказа';
        $doc_id = $this->AnotherPages->getPageId('/cat/order/');
        $letter_xml = $this->AnotherPages->getDocXml($doc_id, 0, $this->lang_id);
        $message = $letter_xml . $table;
        if (!empty($to)) {
            $this->sendMail2($to, $message, $subject);
        }
    }

    private function sendMailToAdmin($item)
    {
        $table = "<table cellspacing='0' cellpadding='2' border='1'>
              <tbody>
                  <tr>
                      <th>Наименование товара</th>
                  </tr>
                  <tr>
                      <th>" . $item['NAME'] . "</th>
                   </tr>
                 </tbody>
                </table>";

        $doc_id = $this->AnotherPages->getPageId('/cat/orderadmin/');

        $message_admin = $this->AnotherPages->getDocXml($doc_id, 0,
                                                        $this->lang_id);

        if (!empty($this->order['SURNAME']))
            $message_admin = str_replace("##lastname##",
                                         $this->order['SURNAME'], $message_admin);
        else
            $message_admin = str_replace("##lastname##", '', $message_admin);

        if (!empty($this->order['NAME']))
            $message_admin = str_replace("##name##", $this->order['NAME'],
                                         $message_admin);
        else
            $message_admin = str_replace("##name##", '', $message_admin);

        if (!empty($this->order['EMAIL']))
            $message_admin = str_replace("##email##", $this->order['EMAIL'],
                                         $message_admin);
        else
            $message_admin = str_replace("##email##", '', $message_admin);

        if (!empty($this->order['TELMOB']))
            $message_admin = str_replace("##phone##", $this->order['TELMOB'],
                                         $message_admin);
        else
            $message_admin = str_replace("##phone##", '', $message_admin);

        if (!empty($this->order['CITY']))
            $message_admin = str_replace("##city##", $this->order['CITY'],
                                         $message_admin);
        else
            $message_admin = str_replace("##city##", '', $message_admin);

        if (!empty($this->order['COMPANY']))
            $message_admin = str_replace("##company##", $this->order['COMPANY'],
                                         $message_admin);
        else
            $message_admin = str_replace("##company##", '', $message_admin);

        if (!empty($this->order['DESCRIPTION']))
            $message_admin = str_replace("##description##",
                                         $this->order['DESCRIPTION'],
                                         $message_admin);
        else
            $message_admin = str_replace("##description##", '', $message_admin);

        $subject = $this->Textes->getSysText('order_mail_subject',
                                             $this->lang_id);
        $message_admin = '<html><head><meta  http-equiv="Content-Type" content="text/html; charset=UTF-8"/></head><body>'
                . $message_admin . $table . '</body></html>';

        $attach = '';
        $name = '';
        $type = '';

        if (!empty($_FILES['feed_attach']['name']) && ($_FILES['feed_attach']['size'] > 0)) {
            $attach = $_FILES['feed_attach']['tmp_name'];
            $name = $_FILES['feed_attach']['name'];
            $type = $_FILES['feed_attach']['type'];
        }

        $manager_emails = $this->getSettingValue('manager_emails');
        if ($manager_emails) {
            $manager_emails_arr = explode(";", $manager_emails);
            if (!empty($manager_emails_arr)) {
                foreach ($manager_emails_arr as $mm) {
                    $mm = trim($mm);
                    if (!empty($mm)) {
                        $this->sendMail2($mm, $message_admin,
                                         $subject['DESCRIPTION'], $attach,
                                         $name, $type);
                    }
                }
            }
        }
    }

    public function successAction()
    {
        $doc_id = $this->AnotherPages->getPageId('/cart/success/');
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
                    )
            );

            $this->domXml->create_element('name', $info['NAME']);
        }
    }

    private function getCatalogArticle()
    {
        $articles_per_block = $this->getSettingValue('articles_per_block') ? $this->getSettingValue('articles_per_block') : 3;
        $data = $this->Article->getCatalogArticle($this->catalog_id,
                                                  $this->lang_id,
                                                  $articles_per_block);
        if (!empty($data)) {
            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
            else
                $lang = '';
            foreach ($data as $val) {
                $this->domXml->create_element('catalog_article', '', 2);
                $this->domXml->set_attribute(array('id' => $val['ARTICLE_ID']
                ));


                $href = '';
                if (!empty($val['URL'])) {
                    $href = $val['URL'];
                } else {
                    $href = $lang . '/articles/all/n/' . $val['ARTICLE_ID'] . '/';
                }

                $this->domXml->create_element('name', $val['NAME']);
                $this->domXml->create_element('date', $val['date']);
                $this->domXml->create_element('url', $href);

                $this->domXml->go_to_parent();
            }
        }
    }

}