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
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            } else {
                $lang = '';
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

                if (!empty($_href) && $is_lang)
                    $href = $lang . $_href;
                elseif (!empty($_href) && !$is_lang)
                    $href = $_href;

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

//                    $catHelper = new Core_Controller_Action_Helper_CatHelper($params, $this->anotherPages, $this->catalogue);
                    $domXml = $this->getItems($cat['CATALOGUE_ID'], $this->params['itemId']);

//                    $this->domXml->appendXML($domXml->getXMLobject());
                }

                $this->domXml->go_to_parent();
            }
        }
    }

    public function getItems($catalogId, $itemId = 0)
    {
        $this->domXml->create_element('itemnode', '', 1);
        $this->domXml->set_tag('//itemnode', true);

        $this->getDocXml($catalogId, 2, true, $this->params['langId']);

        $data = $this->catalogue->getItems($catalogId, $this->params['langId']);
        if (!empty($data)) {
            if ($this->params['langId'] > 0)
                $lang = '/' . $this->params['lang'];
            else
                $lang = '';

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

        return $this->domXml;
    }
} 