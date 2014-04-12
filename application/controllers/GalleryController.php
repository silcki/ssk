<?php
class GalleryController extends CommonBaseController
{
    public $gallery_id;

    public function init()
    {
        parent::init();

        $this->getSysText('page_main');

        $action_name = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

        if ($action_name != 'view') {
            Zend_Controller_Front::getInstance()->getRequest()->setActionName('all');
        }

        $this->gallery_id = $this->_getParam('n', 0);
    }

    public function allAction()
    {
        $ap_id = $this->AnotherPages->getPageByURL('/gallery/');
        $_href = $this->AnotherPages->getSefURLbyOldURL('/gallery/');
        $path = "//main_menu[url[text()='{$_href}']]";

        Zend_Loader::loadClass('MenuHelper');
        $menuHelper = new MenuHelper($this->domXml);
        $menuHelper->setNode($path, 'on_path', '1');

        $this->getDocMeta($ap_id);

        $o_data['ap_id'] = $ap_id;
        $o_data['gal_id'] = $this->gallery_id;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getDocInfo($ap_id);

        $this->getGallaryPath($this->gallery_id);

        $this->getGalleryGroupMeta($this->gallery_id);

        $this->getGalleryGroup($this->gallery_id);

        $this->getGalleryLeftMenu();

        $this->domXml->set_tag('//data', true);
    }

    public function viewAction()
    {
        $info = $this->Gallery->getGroupGalleryInfo($this->gallery_id, $this->lang);
        if (empty($info)) {
            $this->page_404();
        }

        $ap_id = $this->AnotherPages->getPageId('/gallery/');
        $_href = $this->AnotherPages->getSefURLbyOldURL('/gallery/');
        $path = "//main_menu[url[text()='{$_href}']]";

        Zend_Loader::loadClass('MenuHelper');
        $menuHelper = new MenuHelper($this->domXml);
        $menuHelper->setNode($path, 'on_path', '1');

        $this->getDocMeta($ap_id);

        $o_data['ap_id'] = $ap_id;
        $o_data['gal_id'] = $this->gallery_id;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->getDocInfo($ap_id);

        $this->getGalleryGroupMeta($this->gallery_id);

        $this->getGallaryPath($this->gallery_id);

        $this->getGallery($this->gallery_id);

        $this->getGalleryLeftMenu();

        $this->domXml->set_tag('//data', true);
    }

    private function getGalleryGroup($pid, $section = 'gallery_group', $pathIDs = array())
    {
        $gallery = $this->Gallery->getGalleryGroup($pid, $this->lang_id);
        $this->getDocXml($pid, 10, true, $this->lang_id);

        if ($this->lang_id > 0) {
            $lang = '/' . $this->lang;
        } else {
            $lang = '';
        }

        if ($gallery) {
            foreach ($gallery as $view) {
                if (is_array($pathIDs) && !empty($pathIDs)) {
                    $on_path = (in_array($view['GALLERY_GROUP_ID'], $pathIDs) ? 1 : 0);
                } else {
                    $on_path = 0;
                }

                $this->domXml->create_element($section, '', 2);
                $this->domXml->set_attribute(array('id' => $view['GALLERY_GROUP_ID'],
                    'parent_id' => $view['PARENT_ID'],
                    'on_path' => $on_path
                ));

                $subs_count = $this->Gallery->getSubGroupGallery($view['GALLERY_GROUP_ID']);

                if ($subs_count > 0) {
                    $href = $lang . '/gallery/all/n/' . $view['GALLERY_GROUP_ID'] . '/';
                } else {
                    $href = $lang . '/gallery/view/n/' . $view['GALLERY_GROUP_ID'] . '/';
                }

                $_href = $this->AnotherPages->getSefURLbyOldURL($href);

//          if(!empty($_href) && $is_lang) $href = $lang.$_href;
//          elseif(!empty($_href) && !$is_lang) $href = $_href;

                $href = $lang . $_href;

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('description',
                                              $view['DESCRIPTION']);
                $this->domXml->create_element('style',
                                              $this->style[$view['STYLE']]);
                $this->domXml->create_element('url', $href);

                if (!empty($view['IMAGE1']) && strchr($view['IMAGE1'], "#")) {
                    $tmp = split('#', $view['IMAGE1']);
                    $this->domXml->create_element('image1', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0]
                        , 'w' => $tmp[1]
                        , 'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }

                $subcats = $this->Gallery->getGalleryGroup($view['GALLERY_GROUP_ID'],
                                                           $this->lang_id);
                if (!empty($subcats) && $section == 'gallery_tree' && $on_path == 1) {
                    $this->getGalleryGroup($view['GALLERY_GROUP_ID'], $section,
                                           $pathIDs);
                }

                $this->domXml->go_to_parent();
            }
        }
    }

    private function getGallery($id)
    {
        $gallery = $this->Gallery->getGallery($id, $this->lang_id);
        $this->getDocXml($id, 10, true, $this->lang_id);
        if ($gallery) {
            foreach ($gallery as $view) {
                $this->domXml->create_element('gallery', '', 2);
                $this->domXml->set_attribute(array('gallery_id' => $view['GALLERY_ID']
                ));

                $this->domXml->create_element('name', $view['NAME']);
                $this->setXmlNode($view['DESCRIPTION'], 'description');
//          $this->domXml->create_element('description', $view['DESCRIPTION']);

                if (!empty($view['IMAGE1']) && strchr($view['IMAGE1'], "#")) {
                    $tmp = split('#', $view['IMAGE1']);
                    $this->domXml->create_element('image1', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0]
                        , 'w' => $tmp[1]
                        , 'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }

                if (!empty($view['IMAGE2']) && strchr($view['IMAGE2'], "#")) {
                    $tmp = split('#', $view['IMAGE2']);
                    $this->domXml->create_element('image2', '', 2);
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

    private function getDocMeta($id)
    {
        $info = $this->AnotherPages->getDocInfo($id, $this->lang_id);
        if ($info) {
            $this->domXml->create_element('docinfo', '', 2);
            $this->domXml->create_element('name', $info['NAME']);

//            if ($this->gallery_id > 0) {
//                $href = '/gallery/';
//                $_href = $this->AnotherPages->getSefURLbyOldURL($href);
//                if (!empty($_href)) {
//                    $href = $_href;
//                }
//
//                $this->befor_path[0]['name'] = $info['NAME'];
//                $this->befor_path[0]['url'] = $href;
//            }
//            else {
//                $this->befor_path[0]['name'] = $info['NAME'];
//                $this->befor_path[0]['url'] = '';
//            }

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

    private function getGalleryGroupMeta($id)
    {
        $gallery_group_id = $this->Gallery->getGroupGalleryID($id);
        $info = $this->Gallery->getGroupGalleryInfo($id, $this->lang_id);
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

    private function getGalleryLeftMenu()
    {
        $ap_id = $this->AnotherPages->getPageId('/gallery/');
        $info = $this->AnotherPages->getDocInfo($ap_id, $this->lang_id);

        $this->domXml->create_element('gallery_tree', '', 2);
        $this->domXml->set_attribute(array('id' => 0,
            'parent_id' => 0,
            'on_path' => 1
        ));

        if ($this->lang_id > 0) {
            $lang = '/' . $this->lang;
        } else {
            $lang = '';
        }

        $is_lang = false;
        if (!empty($info['URL']) && strpos($info['URL'], 'http://') !== false) {
            $is_lang = true;
            $href = $info['URL'];
        } elseif (!empty($info['URL'])) {
            $href = $info['URL'];
        } elseif (!empty($info['REALCATNAME']) && $info['REALCATNAME'] != '/') {
            $is_lang = true;
            $href = '/doc' . $info['REALCATNAME'];
        } else {
            $is_lang = true;
            $href = '/doc/' . $info['ANOTHER_PAGES_ID'] . '/';
        }

        $_href = $this->AnotherPages->getSefURLbyOldURL($href);
        if (!empty($_href) && $is_lang) {
            $href = $lang . $_href;
        } elseif (!empty($_href) && !$is_lang) {
            $href = $_href;
        }

        $this->domXml->create_element('name', $info['NAME']);
        $this->domXml->create_element('url', $href);


        $pathIDs = $this->Gallery->getAllParents($this->gallery_id);
        $pathIDs[] = $this->gallery_id;
        $this->getGalleryGroup(0, 'gallery_tree', $pathIDs);

        $this->domXml->go_to_parent();

        /* =============================================================== */

        $ap_id = $this->AnotherPages->getPageId('/videogallery/');

        if (!empty($ap_id)) {
            $info = $this->AnotherPages->getDocInfo($ap_id, $this->lang_id);

            $this->domXml->create_element('gallery_tree', '', 2);
            $this->domXml->set_attribute(array('id' => 0,
                'parent_id' => 0,
                'on_path' => 0
            ));

            $href = '';
            $is_lang = false;
            if (!empty($info['URL']) && strpos($info['URL'], 'http://') !== false) {
                $is_lang = true;
                $href = $info['URL'];
            } elseif (!empty($info['URL'])) {
                $href = $info['URL'];
            } elseif (!empty($info['REALCATNAME']) && $info['REALCATNAME'] != '/') {
                $is_lang = true;
                $href = '/doc' . $info['REALCATNAME'];
            } else {
                $is_lang = true;
                $href = '/doc/' . $info['ANOTHER_PAGES_ID'] . '/';
            }

            $_href = $this->AnotherPages->getSefURLbyOldURL($href);
            if (!empty($_href) && $is_lang)
                $href = $lang . $_href;
            elseif (!empty($_href) && !$is_lang)
                $href = $_href;

            $this->domXml->create_element('name', $info['NAME']);
            $this->domXml->create_element('url', $href);

            $this->domXml->go_to_parent();
        }
    }

    private function getGallaryPath($id)
    {
        $ap_id = $this->AnotherPages->getPageId('/gallery/');
        $childs = array();
        if (!empty($id)) {
            $childs[count($childs)] = $id;
            $parent = $id;

            while ($parent > 0) {
                $cat = $this->Gallery->getParents($parent);
                $parent = $cat['PARENT_ID'];
                if ($parent == 0) {
                    break;
                }
                $childs[count($childs)] = $cat['PARENT_ID'];
            }
        }

//        $this->getRootPath();
//        $this->getBeforPath();
        $this->getDocPath($ap_id);

        if (!empty($childs)) {
            $childs = array_reverse($childs);
            if ($this->lang_id > 0) {
                $lang = '/' . $this->lang;
            } else {
                $lang = '';
            }

            foreach ($childs as $view) {
                $parent = $this->Gallery->getParents($view);
                if (empty($parent)) {
                    continue;
                }
                $this->domXml->create_element('breadcrumbs', '', 2);
                $this->domXml->set_attribute(array('id' => $parent['GALLERY_GROUP_ID'],
                        'parent_id' => $parent['PARENT_ID']
                    )
                );

                $href = $lang . '/gallery/all/n/' . $parent['GALLERY_GROUP_ID'] . '/';

                $subs_count = $this->Gallery->getSubGroupGallery($parent['GALLERY_GROUP_ID']);

                $href = '';
                if ($subs_count > 0) {
                    $href = $lang . '/gallery/all/n/' . $parent['GALLERY_GROUP_ID'] . '/';
                } else {
                    $href = $lang . '/gallery/view/n/' . $parent['GALLERY_GROUP_ID'] . '/';
                }

                $_href = $this->AnotherPages->getSefURLbyOldURL($href);

//          if(!empty($_href) && $is_lang) $href = $lang.$_href;
//          elseif(!empty($_href) && !$is_lang) $href = $_href;

                $href = $lang . $_href;

                $this->domXml->create_element('name', trim($parent['NAME']));
                $this->domXml->create_element('url', $href);
                $this->domXml->go_to_parent();
            }
        }

        $this->getAfterPath();
    }

}
