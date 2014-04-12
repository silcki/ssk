<?php

class CatHelper
{

    private $lang_id;
    private $lang;
    private $AnotherPages;
    private $Catalogue;
    private $domXml;

    public function __construct($params = array())
    {
        $this->lang_id = $params['lang_id'];
        $this->lang = $params['lang'];

        Zend_Loader::loadClass('AnotherPages');
        Zend_Loader::loadClass('Catalogue');

        $this->AnotherPages = new AnotherPages();
        $this->Catalogue = new Catalogue();

        $this->domXml = new DomXML();
    }

    public function getItems($catalogId, $itemId = 0)
    {
        $this->domXml->create_element('itemnode', '', 1);
        $this->domXml->set_tag('//itemnode', true);

        $this->getDocXml($catalogId, 2, true, $this->lang_id);

        $data = $this->Catalogue->getItems($catalogId, $this->lang_id);
        if (!empty($data)) {
            if ($this->lang_id > 0)
                $lang = '/' . $this->lang;
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

                $_href = $this->AnotherPages->getSefURLbyOldURL($href);
                if (!empty($_href)) {
                    $href = $lang . $_href;
                }

                $this->domXml->create_element('name', $item['NAME']);
                $this->domXml->create_element('menu_name', $item['MENU_NAME']);
                $this->domXml->create_element('description',
                                              nl2br($item['DESCRIPTION']));
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

    public function getDocXml($id = 0, $type = 0, $tag = false, $lang = 0)
    {
        $doc = $this->AnotherPages->getDocXml($id, $type, $lang);
        $doc = stripslashes($doc);


        if (!empty($doc)) {
            $pattern = '/<a(.+)href="([http:\/\/|https:\/\/]?.+)"/Uis';
            $doc = preg_replace_callback($pattern, 'addSizeText', $doc);

            $pattern = '/(<a.*href=".*\.(\w+)) size=(.+)"/Uis';
            $doc = preg_replace($pattern, '${1}" typeDoc="$2" size="$3"', $doc);

            if ($tag) {
                $txt = "<?xml version=\"1.0\" encoding=\"{$this->domXml->get_encoding()}\"?><!DOCTYPE stylesheet SYSTEM \"symbols.ent\"><txt>" . $doc . "</txt>";
            } else {
                $txt = "<?xml version=\"1.0\" encoding=\"{$this->domXml->get_encoding()}\"?><!DOCTYPE stylesheet SYSTEM \"symbols.ent\">" . $doc;
            }
            $this->domXml->import_node($txt, false);
        }
    }

}