<?php
class HelperAnotherPages extends Core_Controller_Action_Helper_Abstract
{
    /**
     * @var AnotherPages
     */
    protected $anotherPages;

    /**
     * @var array()
     */
    private $_pathMenuIDs;

    public function init()
    {
        $this->anotherPages = $this->getServiceManager()->getModel()->getAnotherPages();
    }

    /**
     * @param int $docId
     * @return $this
     */
    public function initPathIDs($docId)
    {
        $this->_pathMenuIDs = $this->anotherPages->getParents($docId);
        $this->_pathMenuIDs[count($this->_pathMenuIDs)] = $docId;

        return $this;
    }

    /**
     * Метод вывода меню сайта
     */
    public function makeMenu($parentID = 0, $level = 1)
    {
        $menu = $this->anotherPages->getTree($parentID, $this->params['langId']);
        if (!empty($menu)) {
            $lang = '';
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

            foreach ($menu as $view) {
                if (is_array($this->_pathMenuIDs) && !empty($this->_pathMenuIDs)) {
                    $on_path = (in_array($view['ANOTHER_PAGES_ID'], $this->_pathMenuIDs) ? 1 : 0);
                } else {
                    $on_path = 0;
                }

                $this->domXml->create_element('main_menu', '', 2);
                $this->domXml->set_attribute(array('another_pages_id' => $view['ANOTHER_PAGES_ID']
                , 'parent_id' => $view['PARENT_ID']
                , 'is_new_win' => $view['IS_NEW_WIN']
                , 'is_node' => $view['IS_NODE']
                , 'via_js' => $view['VIA_JS']
                , 'on_path' => $on_path
                , 'level' => $level
                ));

                $is_lang = false;
                if (!empty($view['URL']) && strpos($view['URL'], 'http://') !== false) {
                    $is_lang = true;
                    $href = $view['URL'];
                } elseif (!empty($view['URL'])) {
                    $href = $view['URL'];
                } elseif (!empty($view['REALCATNAME']) && $view['REALCATNAME'] != '/') {
                    $is_lang = true;
                    $href = $view['REALCATNAME'];
                } else {
                    $is_lang = true;
                    $href = '/doc/' . $view['ANOTHER_PAGES_ID'] . '/';
                }

                $_href = $this->anotherPages->getSefURLbyOldURL($href);
                if (!empty($_href) && $is_lang) {
                    $href = $lang . $_href;
                } elseif (!empty($_href) && !$is_lang) {
                    $href = $_href;
                }

                /* Заменяем обычные пробелы на неразрывные вида &#160; */
                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('url', $href);
                $this->domXml->create_element('spec_url', $view['URL']);

                if ($view['URL'] == '/cat/all/') {
                    $this->getServiceManager()->getHelper()->getCatalogue()
                        ->setParams($this->params)
                        ->getCatTree(0, 'main_menu');
                } else {
                    $level++;
                    $this->makeMenu($view['ANOTHER_PAGES_ID'], $level);
                    $level--;
                }

                $this->domXml->go_to_parent();
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function getHeader()
    {
        $headers = $this->anotherPages->getHeader($this->params['langId']);
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

        return $this;
    }

    /**
     * @return $this
     */
    public function getLeftBanners()
    {
        $headers = $this->anotherPages->getLeftBanners($this->params['langId']);
        if (!empty($headers)) {
            foreach ($headers as $val) {
                $this->domXml->create_element('left_banner', '', 2);

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

        return $this;
    }

    /**
     * Метод для получения информации о странице
     *
     * @access   public
     * @param    integer $id
     *
     * @return  $this
     */
    public function getDocInfo($docId)
    {
        $info = $this->anotherPages->getDocInfo($docId, $this->params['langId']);
        if ($info) {
            $this->domXml->set_tag('//data', true);

            $this->domXml->create_element('docinfo', '', 2);
            $this->domXml->set_attribute(array('another_pages_id' => $info['ANOTHER_PAGES_ID']
                , 'parent_id' => $info['PARENT_ID']
                )
            );

            $this->domXml->create_element('name', $info['NAME']);

            $this->getDocXml($docId, 0, true, $this->params['langId']);
            $this->domXml->go_to_parent();

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
        }

        return $this;
    }

    /**
     * Получить мета описание
     *
     * @param $ap_id
     *
     * @return $this
     */
    public function getDocMeta($ap_id)
    {
        $info = $this->anotherPages->getDocInfo($ap_id, $this->params['langId']);
        if ($info) {
            $this->domXml->create_element('doc_meta', '', 2);
            $this->domXml->create_element('title', $info['TITLE']);

            $descript = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;", $info['DESCRIPTION']);
            $descript = preg_replace("/\"/", "&#171;", $descript);
            $this->domXml->create_element('description', $descript);

            $keyword = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;", $info['KEYWORDS']);
            $keyword = preg_replace("/\"/", "&#171;", $keyword);
            $this->domXml->create_element('keywords', $keyword);

            $this->domXml->go_to_parent();
        }

        return $this;
    }

    /**
     * Получить бредкрамбс
     *
     * @param int   $id
     * @param array $beforPath
     * @param array $afterPath
     *
     * @return $this
     */
    public function getDocPath($id, $beforPath = array(), $afterPath = array())
    {
        $parent = $this->anotherPages->getPath($id);
        if (!empty($parent)) {
            $lang = '';
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

            $this->getRootPath();
            $this->getBeforPath($beforPath);

            foreach ($parent as $view) {
//                if ($view['PARENT_ID'] > 0 && $view['IS_NODE'] == 0) {
                if ($view['PARENT_ID'] > 0) {
                    $this->domXml->create_element('breadcrumbs', '', 2);
                    $this->domXml->set_attribute(array('id' => $view['ANOTHER_PAGES_ID']
                    , 'parent_id' => $view['PARENT_ID']
                    ));

                    $is_lang = false;
                    if (!empty($view['URL']) && strpos($view['URL'], 'http://') !== false) {
                        $is_lang = true;
                        $href = $view['URL'];
                    } elseif (!empty($view['URL'])) {
                        $href = $view['URL'];
                    } elseif (!empty($view['REALCATNAME']) && $view['REALCATNAME'] != '/') {
                        $is_lang = true;
                        //           $href = '/doc'.$view['REALCATNAME'];
                        $href = $view['REALCATNAME'];
                    } else {
                        $is_lang = true;
                        $href = '/doc/' . $view['ANOTHER_PAGES_ID'] . '/';
                    }

                    $_href = $this->anotherPages->getSefURLbyOldURL($href);
                    if (!empty($_href) && $is_lang) {
                        $href = $lang . $_href;
                    } elseif (!empty($_href) && !$is_lang) {
                        $href = $_href;
                    }

                    $this->domXml->create_element('name', $view['NAME']);
                    $this->domXml->create_element('url', $href);
                    $this->domXml->go_to_parent();
                }
            }

            $this->getAfterPath($afterPath);
        }

        return $this;
    }
} 