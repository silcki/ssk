<?php
class BookletsController extends CommonBaseController
{
    public function init()
    {
        parent::init();
        $this->getSysText('booklets');
        $this->getSysText('page_main');
    }

    public function indexAction()
    {
        $anotherPagesId = $this->AnotherPages->getPageId('/booklets/');

        $_href = $this->AnotherPages->getSefURLbyOldURL('/booklets/');
        $path = "//main_menu[url[text()='{$_href}']]";

        Zend_Loader::loadClass('MenuHelper');
        $menuHelper = new MenuHelper($this->domXml);
        $menuHelper->setNode($path, 'on_path', '1');

        $this->getDocMeta($anotherPagesId);

        $o_data['ap_id'] = $anotherPagesId;
        $o_data['is_vote'] = '';
        $this->openData($o_data);

        $this->domXml->set_tag('//data', true);

        $this->getBooklets();
    }

    public function getDocMeta($anotherPagesId)
    {
        $info = $this->AnotherPages->getDocInfo($anotherPagesId, $this->lang_id);
        if ($info) {
            $this->domXml->create_element('docinfo', '', 2);
            $this->domXml->create_element('name', $info['NAME']);

            $this->domXml->create_element('title', $info['TITLE']);

            $descript = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;",$info['DESCRIPTION']);
            $descript = preg_replace("/\"/", "&#171;", $descript);
            $this->domXml->create_element('description', $descript);

            $keyword = preg_replace("/\"([^\"]*)\"/", "&#171;\\1&#187;", $info['KEYWORDS']);
            $keyword = preg_replace("/\"/", "&#171;", $keyword);
            $this->domXml->create_element('keywords', $keyword);

            $this->domXml->go_to_parent();
        }
    }

    /**
     * Вывод буклетов
     *
     */
    public function getBooklets()
    {
        Zend_Loader::loadClass('Booklets');
        $Booklets = new Booklets();

        $booklets = $Booklets->getBooklets();
        if (!empty($booklets)) {
            foreach ($booklets as $view) {
                $this->domXml->create_element('booklets', '', 2);
                $this->domXml->set_attribute(array('id' => $view['BOOKLETS_ID']
                                            ));

                $this->domXml->create_element('name', $view['NAME']);

                if (!empty($view['IMAGE_NAME'])) {
                    $tmp = explode('#', $view['IMAGE_NAME']);

                    $this->domXml->create_element('image_name', '', 2);
                    $this->domXml->set_attribute(array('src' => $tmp[0],
                        'w' => $tmp[1],
                        'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }

                $fileInfo = array();

                if (!empty($view['PATH_FILE'])) {
                    $path = $_SERVER['DOCUMENT_ROOT'].$view['PATH_FILE'];

                    $pathInfo = pathinfo($path);

                    $fileInfo = array($pathInfo['extension'], $view['PATH_FILE'], filesize($path));
                } else if (!empty($view['FILE_NAME'])){
                    list($_file, $size, $ext) = explode('#', $view['FILE_NAME']);

                    $path = $_SERVER['DOCUMENT_ROOT'].'/images/booklets/'.$_file;
                    $fileInfo = array($ext, $path, $size);
                }

                if (!empty($fileInfo)) {
                    $this->domXml->create_element('result_path', '', 2);
                    $this->domXml->set_attribute(array('src' => $fileInfo[1],
                                                       'size' => convertSize($fileInfo[2]),
                                                       'ext' => $fileInfo[0],
                                                        ));
                    $this->domXml->go_to_parent();
                }

                $this->getBookletsPages($view['BOOKLETS_ID']);

                $this->domXml->go_to_parent();
            }
        }
    }

    public function getBookletsPages($id)
    {
        Zend_Loader::loadClass('Booklets');
        $Booklets = new Booklets();

        $bookletsPages = $Booklets->getBookletsPages($id);
        if (!empty($bookletsPages)) {
            foreach ($bookletsPages as $view) {
                $this->domXml->create_element('booklets_pages', '', 2);
                $this->domXml->set_attribute(array('id' => $view['BOOKLETS_PAGES_ID']
                ));

                $this->domXml->create_element('name', $view['NAME']);

                $this->setXmlNode($view['DESCRIPTION'], 'description');

                $this->domXml->go_to_parent();
            }
        }
    }
}