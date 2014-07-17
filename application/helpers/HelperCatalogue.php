<?php
class HelperCatalogue extends Core_Controller_Action_Helper_Abstract
{
    /**
     * @var Catalogue
     */
    protected $catalogue;

    /**
     * @var AnotherPages
     */
    protected $anotherPages;

    /**
     * @var Textes
     */
    protected $textes;

    /**
     * @var array
     */
    private $_formData;

    private $_style = array(
        0 => 'red'
      , 1 => 'blue'
      , 2 => 'green'
      , 3 => 'grey'
      , 4 => 'yellow'
      , 5 => 'fiolet'
      , 6 => 'yellow2'
            );

    /**
     * @var array
     */
    private $_pathIDs;

    public function init()
    {
        $this->catalogue = $this->getServiceManager()->getModel()->getCatalogue();
        $this->anotherPages = $this->getServiceManager()->getModel()->getAnotherPages();
        $this->textes = $this->getServiceManager()->getModel()->getTextes();
    }

    /**
     * @param $catalogId
     *
     * @return bool
     */
    public function isActive($catalogId)
    {
        $res = $this->catalogue->getParents($catalogId);
        if (($catalogId > 0 && empty($res))) {
            return false;
        }
        
        return true;
    }

    /**
     * @return Catalogue
     */
    public function getCatalogueModel()
    {
        return $this->catalogue;
    }

    /**
     * @return array
     */
    public function getFormData()
    {
        return $this->_formData;
    }

    /**
     * @param $catalogId
     *
     * @return $this
     */
    public function initPathIDs($catalogId)
    {
        $this->_pathIDs = $this->catalogue->getAllParents($catalogId, $this->_pathIDs);
        $this->_pathIDs[count($this->_pathIDs)] = $catalogId;

        return $this;
    }

    public function getCatTree($parentId = 0, $section = 'cattree')
    {
        $cats = $this->catalogue->getTree($parentId, $this->params['langId']);

        if (!empty($cats)) {
            $lang = '';
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

            foreach ($cats as $cat) {
                $on_path = 0;
                if (is_array($this->_pathIDs) && !empty($this->_pathIDs)) {
                    $on_path = (in_array($cat['CATALOGUE_ID'], $this->_pathIDs) ? 1 : 0);
                }

                $children_item_count = $this->catalogue->getItemsCount($cat['CATALOGUE_ID']);

                $this->domXml->create_element($section, '', 2);
                $this->domXml->set_attribute(array('catalogue_id' => $cat['CATALOGUE_ID']
                    , 'parent_id' => $cat['PARENT_ID']
                    , 'in_main' => $cat['STATUS_MAIN']
                    , 'in_menu' => $cat['IN_MENU']
                    , 'on_path' => $on_path
                    , 'item_count' => $children_item_count
                    )
                );

                $is_lang = false;
                if (!empty($cat['URL']) && strpos($cat['URL'], 'http://') !== false) {
                    $is_lang = true;
                    $href = $cat['URL'];
                } elseif (!empty($cat['URL'])) {
                    $href = $cat['URL'];
                } else {
                    $href = '/cat/view/n/' . $cat['CATALOGUE_ID'] . '/';
                    $is_lang = true;
                }

                $_href = $this->anotherPages->getSefURLbyOldURL($href);

                if (!empty($_href) && $is_lang) {
                    $href = $lang . $_href;
                } elseif (!empty($_href) && !$is_lang) {
                    $href = $_href;
                }                    

                $this->domXml->create_element('name', $cat['NAME']);
                $this->domXml->create_element('catname', $cat['CATNAME']);
                $this->domXml->create_element('realcatname', $cat['REALCATNAME']);
                $this->domXml->create_element('style', $this->_style[$cat['COLOR_STYLE']]);
                $this->domXml->create_element('url', $href, 3, array(), 1);

                if (!empty($cat['IMAGE1']) && strchr($cat['IMAGE1'], "#")) {
                    $tmp = explode('#', $cat['IMAGE1']);
                    $this->domXml->create_element('image1', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0],
                            'w' => $tmp[1],
                            'h' => $tmp[2]
                        )
                    );
                    $this->domXml->go_to_parent();
                }

                if (!empty($cat['IMAGE2']) && strchr($cat['IMAGE2'], "#")) {
                    $tmp = explode('#', $cat['IMAGE2']);
                    $this->domXml->create_element('image2', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0],
                            'w' => $tmp[1],
                            'h' => $tmp[2]
                        )
                    );
                    $this->domXml->go_to_parent();
                }

                if ($section == 'cattree') {
                    $this->getCatTree($cat['CATALOGUE_ID'], $section);
                }

                if ($children_item_count > 0) {
                    $params['lang_id'] = $this->params['langId'];
                    $params['lang'] = $this->params['lang'];

                    $this->getCattreeItems($cat['CATALOGUE_ID'], $this->params['itemId']);
                }

                $this->domXml->go_to_parent();
            }
        }
        
        return $this;
    }

    public function getCattreeItems($catalogId, $itemId = 0, $full = false)
    {
        $item_koef_small = $this->getSettingValue('item_koef_small', 1);

        $this->domXml->create_element('itemnode', '', 2);
        if ($full) {
            $this->getDocXml($catalogId, 2, true, $this->params['langId']);
        }

        $data = $this->catalogue->getItems($catalogId, $this->params['langId']);
        if (!empty($data)) {
            $lang = '';
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

            foreach ($data as $item) {
                $this->domXml->create_element('items', '', 2);
                $this->domXml->set_attribute(array('catalogue_id' => $catalogId
                    , 'item_id' => $item['ITEM_ID']
                    , 'in_main' => $item['STATUS_MAIN']
                    , 'on_path' => ($itemId == $item['ITEM_ID']) ? 1:0
                    )
                );

                $href = $lang . '/cat/item/n/' . $catalogId . '/it/' . $item['ITEM_ID'] . '/';

                $_href = $this->anotherPages->getSefURLbyOldURL($href);
                if (!empty($_href)) {
                    $href = $lang . $_href;
                }

                $this->domXml->create_element('name', $item['NAME']);
                $this->domXml->create_element('menu_name', $item['MENU_NAME']);
//                $this->domXml->create_element('description', nl2br($item['DESCRIPTION']));
                $this->domXml->create_element('url', $href);

                $this->setXmlNode($item['DESCRIPTION'], 'description');

                if (!empty($item['IMAGE']) && strchr($item['IMAGE'], "#")) {
                    $tmp = explode('#', $item['IMAGE']);
                    $this->domXml->create_element('image', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0],
                            'w' => round($tmp[1] * $item_koef_small),
                            'h' => round($tmp[2] * $item_koef_small)
                        )
                    );
                    $this->domXml->go_to_parent();
                }

                $this->domXml->go_to_parent();
            }
        }

        $this->domXml->go_to_parent();

        return $this;
    }

    /**
     * @param $id
     */
    public function getSubCats($id)
    {
        $this->getDocXml($id, 2, true, $this->params['langId']);
        $childs = $this->catalogue->getChildren($id);
        if (!empty($childs)) {
            $lang = '';
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

            foreach ($childs as $cat) {
                $data = $this->catalogue->getCatInfo($cat, $this->params['langId']);
                if (!empty($data)) {
                    $this->domXml->create_element('catalogue', '', 2);
                    $this->domXml->set_attribute(array('catalogue_id' => $data['CATALOGUE_ID']
                    , 'parent_id' => $data['PARENT_ID']
                    ));

                    $children_item_count = $this->catalogue->getItemsCount($data['CATALOGUE_ID']);

                    $is_lang = false;
                    if (($data['ITEM_IS_DESCR'] == 1) && ($children_item_count == 1)) {
                        $item_id = $this->catalogue->getCatFirstItems($data['CATALOGUE_ID']);
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

                    $_href = $this->anotherPages->getSefURLbyOldURL($href);

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

                    $subcats = $this->catalogue->getChildren($data['CATALOGUE_ID']);
                    if (!empty($subcats)) {
                        $this->getSubCats($data['CATALOGUE_ID']);
                    }

                    $this->domXml->go_to_parent();
                }
            }
        }
        
        return $this;
    }

    public function getItems($id)
    {
        $this->getDocXml($id, 2, true, $this->params['langId']);
        $data = $this->catalogue->getItems($id, $this->params['langId']);
        if (!empty($data)) {
            $lang = '';
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

            foreach ($data as $item) {
                $this->domXml->create_element('items', '', 2);
                $this->domXml->set_attribute(array('catalogue_id' => $id
                    , 'item_id' => $item['ITEM_ID']
                    )
                );

                $href = $lang . '/cat/item/n/' . $id . '/it/' . $item['ITEM_ID'] . '/';

                $_href = $this->anotherPages->getSefURLbyOldURL($href);
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
        
        return $this;
    }

    /**
     * @param $id
     * 
     * @return $this
     */
    public function getItem($id)
    {
        $data = $this->catalogue->getItemInfo($id, $this->params['langId']);

        if (!empty($data)) {
            $children_item_count = $this->catalogue->getItemsCount($data['CATALOGUE_ID']);

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

            $this->getDocXml($id, 3, true, $this->params['langId']);

            $elements = $this->catalogue->getItemElementsInfo($id, $this->params['langId']);
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

            $item_fotos = $this->catalogue->getItemFotos($id, $this->params['langId']);
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
        
        return $this;
    }

    /**
     * 
     */
    public function getCatAllInfo()
    {
        $docId = $this->anotherPages->getPageByURL('/cat/');
        if (!empty($docId)) {
            $docInfo = $this->anotherPages->getDocInfo($docId, $this->params['lang']);

            $this->domXml->set_tag('//data', true);

            $this->befor_path[0]['name'] = $docInfo['NAME'];
            $this->befor_path[0]['url'] = 'cat/';

            $this->domXml->create_element('docinfo', '', 2);
            $this->domXml->create_element('name', $docInfo['NAME']);

            $this->domXml->set_tag('//data', true);
            $this->domXml->create_element('doc_meta', '', 2);
            $this->domXml->create_element('title', $docInfo['TITLE']);
            $this->domXml->create_element('description', $docInfo['DESCRIPTION']);
            $this->domXml->create_element('keywords', $docInfo['KEYWORDS']);

            $this->domXml->go_to_parent();

            $this->domXml->set_tag('//data', true);
            $this->domXml->create_element('sectioninfo', '', 2);
            $image = $this->anotherPages->getSectionImage($docId);

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
        
        return $this;
    }

    /**
     * @return $this
     */
    public function getItemMeta($itemId)
    {
        $data = $this->catalogue->getItemMeta($itemId, $this->params['langId']);
        if (!empty($data)) {
            $this->domXml->create_element('doc_meta', '', 2);
            $this->domXml->create_element('title', $data['HTML_TITLE']);

            $descript = preg_replace("/\"([^\"]*)\"/","&#171;\\1&#187;",$data['HTML_KEYWORDS']);
            $descript = preg_replace("/\"/","&#171;",$descript);
            $this->domXml->create_element('description',$descript);

            $keyword = preg_replace("/\"([^\"]*)\"/","&#171;\\1&#187;",$data['HTML_DESCRIPTION']);
            $keyword = preg_replace("/\"/","&#171;",$keyword);
            $this->domXml->create_element('keywords',$keyword);

            $this->domXml->go_to_parent();
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function getCatInfo($catalogId)
    {
        $catinfo = $this->catalogue->getCatInfo($catalogId, $this->params['langId']);
        $cat_counts = $this->catalogue->getChildrenCount($catalogId);
        $item_counts = $this->catalogue->getItemsCount($catalogId);

        $count = ($item_counts > 0) ? $item_counts : $cat_counts;

        $this->domXml->set_tag('//data', true);
        $this->domXml->create_element('catinfo', '', 2);
        $this->domXml->set_attribute(
            array(
                'catalogue_id' => $catinfo['CATALOGUE_ID']
              , 'cat_counts' => $count
            )
        );

        $this->domXml->create_element('name', $catinfo['NAME']);
        $this->domXml->create_element('catname', $catinfo['CATNAME']);
        $this->domXml->create_element('realcatname', $catinfo['REALCATNAME']);
        $this->domXml->create_element('url', $catinfo['URL']);
        $this->setXmlNode($catinfo['DESCRIPTION'], 'description');
        $this->domXml->go_to_parent();

        $this->domXml->create_element('doc_meta', '', 2);
        $this->domXml->create_element('title', $catinfo['NAME'] . ' ' . $catinfo['TITLE']);

        $descript = preg_replace("/\"([^\"]*)\"/","&#171;\\1&#187;",$catinfo['HTML_DESCRIPTION']);
        $descript = preg_replace("/\"/","&#171;",$descript);
        $this->domXml->create_element('description',$descript);

        $keyword = preg_replace("/\"([^\"]*)\"/","&#171;\\1&#187;",$catinfo['HTML_KEYWORDS']);
        $keyword = preg_replace("/\"/","&#171;",$keyword);
        $this->domXml->create_element('keywords',$keyword);
        $this->domXml->go_to_parent();

        $this->getSectionInfo($catalogId);

        return $this;
    }
    
    public function getSectionInfo($catalogId)
    {
        $this->domXml->set_tag('//data', true);
        $this->domXml->create_element('sectioninfo', '', 2);
        $image = $this->catalogue->getSectionImage($catalogId);
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

    /**
     * @param $id
     * @param array $beforPath
     * @param array $afterPath
     * 
     * @return $this
     */
    public function getPath($id, $beforPath = array(), $afterPath = array())
    {
        $childs = array();
        $childs[count($childs)] = $id;
        $parent = $id;

        while ($parent > 0) {
            $cat = $this->catalogue->getParents($parent, $this->params['langId']);
            $parent = $cat['PARENT_ID'];
            if ($parent == 0) {
                break;
            }                
            $childs[count($childs)] = $cat['PARENT_ID'];
        }

        $this->getRootPath();
        $this->getBeforPath($beforPath);

        if (!empty($childs)) {
            $childs = array_reverse($childs);
            $lang = '';
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

            foreach ($childs as $view) {
                $parent = $this->catalogue->getParents($view, $this->params['langId']);
                if (!empty($parent)) {
                    $this->domXml->create_element('breadcrumbs', '', 2);
                    $this->domXml->set_attribute(array(
                            'id' => $parent['CATALOGUE_ID'],
                            'parent_id' => $parent['PARENT_ID']
                        )
                    );

                    $children_item_count = $this->catalogue->getItemsCount($parent['CATALOGUE_ID']);

                    $is_lang = true;
                    if ($children_item_count > 0) {                        
                        $href = '/cat/view/n/' . $parent['CATALOGUE_ID'] . '/';
                    } else {
                        if (!empty($parent['URL'])) {
                            $href = $parent['URL'];
                        } elseif (!empty($parent['REALCATNAME']) && $parent['REALCATNAME'] != '/') {
                            $href = '/cat' . $parent['REALCATNAME'];
                        } else {
                            $href = '/cat/' . $parent['CATALOGUE_ID'] . '/';
                        }
                    }

                    $_href = $this->anotherPages->getSefURLbyOldURL($href);
                    if (!empty($_href) && $is_lang) {
                        $href = $lang . $_href;
                    } elseif (!empty($_href) && !$is_lang) {
                        $href = $_href;
                    }                        

                    $this->domXml->create_element('name', trim($parent['NAME']));
                    $this->domXml->create_element('url', $href);
                    $this->domXml->go_to_parent();
                }
            }
        }

        $this->getAfterPath($afterPath);
        
        return $this;
    }

    public function validateSend($request)
    {
        if (!$request->isPost()) {
            return false;
        }

        if (Core_Controller_Action_Helper_Captcha::validateCaptcha(new Zend_Controller_Request_Http())) {
            return false;
        }

        $this->_formData['NAME'] = $request->getPost('name');
        $this->_formData['SURNAME'] = $request->getPost('surname');
        $this->_formData['TELMOB'] = $request->getPost('telmob');
        $this->_formData['COMPANY'] = $request->getPost('company');
        $this->_formData['DESCRIPTION'] = $request->getPost('description');
        $this->_formData['CITY'] = $request->getPost('city');
        $this->_formData['EMAIL'] = $request->getPost('email');

        return true;
    }

    public function sendMailToUser($item)
    {
        $zakaz_id = $this->catalogue->insertZakaz($this->_formData);
        if (!empty($itemId)) {
            $zakaz_item = array();

            $zakaz_item['ZAKAZ_ID'] = $zakaz_id;
            $zakaz_item['CATALOGUE_ID'] = $item['CATALOGUE_ID'];
            $zakaz_item['NAME'] = $item['NAME'];
            $zakaz_item['ITEM_ID'] = $itemId;
            $this->catalogue->insertOrder($zakaz_item);
        }

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

        $to = $this->_formData['EMAIL'];
        $subject = 'Оформление заказа';
        $doc_id = $this->anotherPages->getPageId('/cat/order/');
        $letter_xml = $this->anotherPages->getDocXml($doc_id, 0, $this->params['langId']);
        $message = $letter_xml . $table;
        if (!empty($to)) {
            $email_from = $this->getSettingValue('email_from');
            $patrern = '/(.*)<?([a-zA-Z0-9\-\_]+\@[a-zA-Z0-9\-\_]+(\.[a-zA-Z0-9]+?)+?)>?/U';
            preg_match($patrern, $email_from, $arr);

            $params['mailerFrom'] = empty($arr[2]) ? '' : trim($arr[2]);
            $params['mailerFromName'] = empty($arr[1]) ? '' : trim($arr[1]);

            $params = array_merge($params, $this->getMailTrasportData());

            $params['to'] = $to;
            $params['message'] = $message;
            $params['subject'] = $subject;

            Core_Controller_Action_Helper_Mailer::send($params);
        }
    }

    public function sendMailToAdmin($item)
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

        $doc_id = $this->anotherPages->getPageId('/cat/orderadmin/');

        $message_admin = $this->anotherPages->getDocXml($doc_id, 0, $this->params['langId']);

        $message_admin = str_replace("##lastname##", $this->_formData['SURNAME'], $message_admin);
        $message_admin = str_replace("##name##", $this->_formData['NAME'], $message_admin);
        $message_admin = str_replace("##email##", $this->_formData['EMAIL'], $message_admin);
        $message_admin = str_replace("##phone##", $this->_formData['TELMOB'], $message_admin);
        $message_admin = str_replace("##city##", $this->_formData['CITY'], $message_admin);
        $message_admin = str_replace("##company##", $this->_formData['COMPANY'], $message_admin);
        $message_admin = str_replace("##description##", $this->_formData['DESCRIPTION'], $message_admin);

        $subject = $this->getServiceManager()->getModel()->getTextes()->getSysText('order_mail_subject', $this->params['langId']);
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
                        $this->sendMail2($mm, $message_admin, $subject['DESCRIPTION'], $attach, $name, $type);
                    }
                }
            }
        }
    }
} 