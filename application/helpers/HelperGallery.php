<?php
class HelperGallery extends Core_Controller_Action_Helper_Abstract
{
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
     * @var Gallery
     */
    protected $gallery;

    /**
     * @var AnotherPages
     */
    protected $anotherPages;

    public function init()
    {
        $this->gallery = $this->getServiceManager()->getModel()->getGallery();
        $this->anotherPages = $this->getServiceManager()->getModel()->getAnotherPages();
    }

    public function isActive($galleryId)
    {
        $info = $this->gallery->getGroupGalleryInfo($galleryId, $this->params['lang']);

        return !empty($info);
    }

    /**
     * @param $pid
     * @param string $section
     * @param array $pathIDs
     *
     * @return $this
     */
    public function getGalleryGroup($pid, $section = 'gallery_group', $pathIDs = array())
    {
        $gallery = $this->gallery->getGalleryGroup($pid, $this->params['langId']);
        $this->getDocXml($pid, 10, true, $this->params['langId']);

        $lang = '';
        if ($this->params['langId'] > 0) {
            $lang = '/' . $this->params['lang'];
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

                $subs_count = $this->gallery->getSubGroupGallery($view['GALLERY_GROUP_ID']);

                if ($subs_count > 0) {
                    $href = $lang . '/gallery/all/n/' . $view['GALLERY_GROUP_ID'] . '/';
                } else {
                    $href = $lang . '/gallery/view/n/' . $view['GALLERY_GROUP_ID'] . '/';
                }

                $_href = $this->anotherPages->getSefURLbyOldURL($href);
                $href = $lang . $_href;

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('description', $view['DESCRIPTION']);
                $this->domXml->create_element('style', $this->_style[$view['STYLE']]);
                $this->domXml->create_element('url', $href);

                if (!empty($view['IMAGE1']) && strchr($view['IMAGE1'], "#")) {
                    $tmp = explode('#', $view['IMAGE1']);
                    $this->domXml->create_element('image1', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0]
                    , 'w' => $tmp[1]
                    , 'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }

                $subcats = $this->gallery->getGalleryGroup($view['GALLERY_GROUP_ID'], $this->params['langId']);

                if (!empty($subcats) && $section == 'gallery_tree' && $on_path == 1) {
                    $this->getGalleryGroup($view['GALLERY_GROUP_ID'], $section, $pathIDs);
                }

                $this->domXml->go_to_parent();
            }
        }

        return $this;
    }

    /**
     * @param $galleryId
     *
     * @return $this
     */
    public function getGallery($galleryId)
    {
        $gallery = $this->gallery->getGallery($galleryId, $this->params['langId']);

        $this->getDocXml($galleryId, 10, true, $this->params['langId']);

        if ($gallery) {
            foreach ($gallery as $view) {
                $this->domXml->create_element('gallery', '', 2);
                $this->domXml->set_attribute(array('gallery_id' => $view['GALLERY_ID']
                ));

                $this->domXml->create_element('name', $view['NAME']);
                $this->setXmlNode($view['DESCRIPTION'], 'description');

                if (!empty($view['IMAGE1']) && strchr($view['IMAGE1'], "#")) {
                    $tmp = explode('#', $view['IMAGE1']);
                    $this->domXml->create_element('image1', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0]
                    , 'w' => $tmp[1]
                    , 'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }

                if (!empty($view['IMAGE2']) && strchr($view['IMAGE2'], "#")) {
                    $tmp = explode('#', $view['IMAGE2']);
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

        return $this;
    }

    /**
     * @param $galleryId
     *
     * @return $this
     */
    public function getGalleryGroupMeta($galleryId)
    {
        $info = $this->gallery->getGroupGalleryInfo($galleryId, $this->params['langId']);
        if ($info) {
            $this->domXml->create_element('doc_meta', '', 2);
            $this->domXml->create_element('name', $info['NAME']);

            $this->domXml->create_element('title', $info['NAME']);

            $descript = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;", $info['DESCRIPTION']);
            $descript = preg_replace("/\"/", "&#171;", $descript);
            $this->domXml->create_element('description', $descript);

            $keyword = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;", $info['DESCRIPTION']);
            $keyword = preg_replace("/\"/", "&#171;", $keyword);
            $this->domXml->create_element('keywords', $keyword);

            $this->domXml->go_to_parent();
        }

        return $this;
    }

    /**
     * @param $galleryId
     *
     * @return $this
     */
    public function getGalleryLeftMenu($galleryId)
    {
        $ap_id = $this->anotherPages->getPageId('/gallery/');
        $info = $this->anotherPages->getDocInfo($ap_id, $this->params['langId']);

        $this->domXml->create_element('gallery_tree', '', 2);
        $this->domXml->set_attribute(array('id' => 0,
            'parent_id' => 0,
            'on_path' => 1
        ));

        $lang = '';
        if ($this->params['langId'] > 0) {
            $lang = '/' . $this->params['lang'];
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

        $_href = $this->anotherPages->getSefURLbyOldURL($href);
        if (!empty($_href) && $is_lang) {
            $href = $lang . $_href;
        } elseif (!empty($_href) && !$is_lang) {
            $href = $_href;
        }

        $this->domXml->create_element('name', $info['NAME']);
        $this->domXml->create_element('url', $href);


        $pathIDs = $this->gallery->getAllParents($galleryId);
        $pathIDs[] = $galleryId;

        $this->getGalleryGroup(0, 'gallery_tree', $pathIDs);

        $this->domXml->go_to_parent();

        /* =============================================================== */

        $ap_id = $this->anotherPages->getPageId('/videogallery/');

        if (!empty($ap_id)) {
            $info = $this->anotherPages->getDocInfo($ap_id, $this->params['langId']);

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

            $_href = $this->anotherPages->getSefURLbyOldURL($href);
            if (!empty($_href) && $is_lang) {
                $href = $lang . $_href;
            } elseif (!empty($_href) && !$is_lang) {
                $href = $_href;
            }

            $this->domXml->create_element('name', $info['NAME']);
            $this->domXml->create_element('url', $href);

            $this->domXml->go_to_parent();
        }

        return $this;
    }

    /**
     * @param $galleryId
     *
     * @return $this
     */
    public function getGallaryPath($galleryId)
    {
        $childs = array();
        if (!empty($galleryId)) {
            $childs[count($childs)] = $galleryId;
            $parent = $galleryId;

            while ($parent > 0) {
                $cat = $this->gallery->getParents($parent);
                $parent = $cat['PARENT_ID'];
                if ($parent == 0) {
                    break;
                }
                $childs[count($childs)] = $cat['PARENT_ID'];
            }
        }

        if (!empty($childs)) {
            $childs = array_reverse($childs);

            $lang = '';
            if ($this->params['langId'] > 0) {
                $lang = '/' . $this->params['lang'];
            }

            foreach ($childs as $view) {
                $parent = $this->gallery->getParents($view);
                if (empty($parent)) {
                    continue;
                }
                $this->domXml->create_element('breadcrumbs', '', 2);
                $this->domXml->set_attribute(array(
                        'id' => $parent['GALLERY_GROUP_ID'],
                        'parent_id' => $parent['PARENT_ID']
                    )
                );

                $subs_count = $this->gallery->getSubGroupGallery($parent['GALLERY_GROUP_ID']);

                if ($subs_count > 0) {
                    $href = $lang . '/gallery/all/n/' . $parent['GALLERY_GROUP_ID'] . '/';
                } else {
                    $href = $lang . '/gallery/view/n/' . $parent['GALLERY_GROUP_ID'] . '/';
                }

                $_href = $this->anotherPages->getSefURLbyOldURL($href);

                $href = $lang . $_href;

                $this->domXml->create_element('name', trim($parent['NAME']));
                $this->domXml->create_element('url', $href);
                $this->domXml->go_to_parent();
            }
        }

        return $this;
    }
} 