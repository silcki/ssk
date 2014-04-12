<?php

class DomXML
{

    private $xml;
    private $tag;
    private $root;
    private $subroot;
    private $encoding;

    function __construct($version = '1.0', $encod = 'UTF-8')
    {
        $imp = new DOMImplementation;
        $dtd = $imp->createDocumentType('xsl:stylesheet', '', 'symbols.ent');
        $this->xml = $imp->createDocument("", "", $dtd);
        $this->xml->encoding = $encod;
        $this->xml->version = $version;
        $this->xml->standalone = false;
        $this->xml->resolveExternals = true;
        $this->xml->substituteEntities = true;
        $this->encoding = $encod;
    }

    public function getPointer()
    {
        return $this->xml;
    }

    public function getXML()
    {
        return $this->xml->saveXML();
    }

    public function getXMLobject()
    {
        return $this->xml;
    }

    public function saveXML($file)
    {
        $this->xml->save($file);
    }

    public function load_xml($file)
    {
        $this->xml->load($file);
    }

    public function get_encoding()
    {
        return $this->encoding;
    }

    private function string_convert($xml)
    {
        if (!empty($this->encoding) && $this->encoding != "UTF-8") {
            try {
                $xml = iconv($this->encoding, "UTF-8", $xml);
            } catch (Exception $e) {
                echo $e;
            }
        }
        return $xml;
    }

    public function create_element($tag_name, $xml = "", $mode = 0, $attripute = array(), $cdata = 0)
    {
        $xml = $this->string_convert($xml);

        switch ($mode) {
            case 0:
                if ($this->subroot) {
                    $this->subroot->appendChild($this->xml->createElement($tag_name, $xml));
                } else {
                    $this->root->appendChild($this->xml->createElement($tag_name, $xml));
                }
                break;

            case 1:
                if ($this->root) {
                    $this->subroot = &$this->root->appendChild($this->xml->createElement($tag_name, $xml));
                } else {
                    $this->root = &$this->xml->appendChild($this->xml->createElement($tag_name, $xml));
                }
                break;

            case 2:
                if ($this->subroot) {
                    $this->subroot = &$this->subroot->appendChild($this->xml->createElement($tag_name, $xml));
                } else {
                    $this->root = &$this->root->appendChild($this->xml->createElement($tag_name, $xml));
                }
                break;

            case 3:
                if ($this->subroot) {
                    $temp_ = $this->subroot;
                    if ($cdata == 1) {
                        $this->subroot = &$this->subroot->appendChild($this->xml->createElement($tag_name));
                        $value = $this->xml->createTextNode($xml);
                        $this->subroot->appendChild($value);
                    } else {
                        $this->subroot = &$this->subroot->appendChild($this->xml->createElement($tag_name, $xml));
                        if (!empty($attripute) && is_array($attripute)) {
                            foreach ($attripute as $key => $value) {
                                $this->subroot->setAttribute($key, $this->string_convert($value));
                            }
                        }
                    }
                    $this->subroot = $temp_;
                } else {
                    $temp_ = $this->root;
                    if ($cdata == 1) {
                        $this->root = &$this->root->appendChild($this->xml->createElement($tag_name));
                        $value = $this->xml->createTextNode($xml);
                        $this->root->appendChild($value);
                    } else {
                        $this->root = &$this->root->appendChild($this->xml->createElement($tag_name, $xml));
                        if (!empty($attripute) && is_array($attripute)) {
                            foreach ($attripute as $key => $value) {
                                $this->root->setAttribute($key, $this->string_convert($value));
                            }
                        }
                    }
                    $this->root = $temp_;
                }
                break;

            default:
                die('The root unit is not specified');
                break;
        }
    }

    public function set_attribute($attripute)
    {
        if (!empty($attripute) && is_array($attripute)) {
            foreach ($attripute as $key => $value) {
                if ($this->subroot) {
                    $this->subroot->setAttribute($key, $this->string_convert($value));
                } else {
                    $this->root->setAttribute($key, $this->string_convert($value));
                }
            }
        }
    }

    public function get_attr_value($name)
    {
        if ($this->subroot->hasAttribute($name))
            return $this->subroot->getAttribute($name);
    }

    public function get_tag_value()
    {
        return $this->subroot->textContent;
    }

    public function set_tag($tag_name, $query = false, $position = 0)
    {

        if (!$query) {
            $items = $this->xml->getElementsByTagName($tag_name);
            $this->subroot = & $items->item(0);
        } else {
            $xpath = new DOMXPath($this->xml);

            if ($xpath->evaluate($tag_name)) {
                $r = $xpath->query($tag_name);
                if ($r->item($position)) {
                    $this->subroot = & $r->item($position);
                }
            }
        }
    }

    public function import_node($xml_node, $cdata = false)
    {
        if ($cdata) {
            $xml_node = $this->string_convert($xml_node);
            $cdata = $this->subroot->ownerDocument->createCDATASection($xml_node);
            $this->subroot->appendChild($cdata);
        } else {
            //          $xml_node = SCMF::removeHTMLentity($xml_node);

            $imp = new DOMImplementation;
            $dtd = $imp->createDocumentType('xsl:stylesheet', '', 'templates/symbols.ent');
            $xmlPost = $imp->createDocument("", "", $dtd);
            $xmlPost->resolveExternals = true;
            $xmlPost->substituteEntities = true;
            $xmlPost->encoding = $this->encoding;
            $xmlPost->version = '1.0';
            $xmlPost->standalone = false;

            $xmlPost->loadXML($xml_node);
            $node = $this->xml->importNode($xmlPost->documentElement, true);
            $this->subroot->appendChild($node);
        }
    }

    function go_inside_tree($name_tag)
    {
        if (!$this->subroot->hasChildNodes())
            return false;

        $child = &$this->subroot[0]->firstChild;

        while ($child) {
            if ($name_tag == $child->nodeName) {
                $this->subroot[0] = $child;
                break;
            }
            $child = & $child->nextSibling;
        }
    }

    function set_curent_node()
    {

    }

    function go_to_parent()
    {
        $this->subroot = &$this->subroot->parentNode;
    }

    function set_param($tag_param, $encoding = "")
    {
        $atribut = $this->subroot[0];
        $atribut = $atribut->firstChild;
        if ($atribut == null) {
            $atribut = $this->subroot[0];
        }

        $tag_param = $this->string_convert($encoding, $tag_param);
        $atribut->set_content($tag_param);
    }

    function clone_elem($elem_para, $anchor = true)
    {
        $name_node = $elem_para->node_name();
        $param = false;
        $atrib = $elem_para->has_attributes();
        if (!empty($atrib)) {
            //print_r($elem_para->attributes());
            $atrib = $elem_para->attributes();
            foreach ($atrib as $value) {
                $param[$value->name] = $value->value;
            }
        }
        $content[$name_node] = "";
        $this->add_child_node($content, $param, $anchor);

        $atrib = $elem_para->has_child_nodes();
        if (!empty($atrib)) {
            $atrib = $elem_para->child_nodes();
            //**************
            foreach ($atrib as $value) {
                $a = $value->has_child_nodes();

                if (3 == $value->node_type())
                    $this->tag[0]->set_content($value->content);

                if (1 == $value->node_type()) {
                    $this->clone_elem($value);
                    $this->go_to_parent();
                }
            }
        }
    }

    function clone_node($xml_para)
    {
        $root = $xml_para->document_element();
        $this->clone_elem($root);
        //print_r($xml_para);
    }

    function clear_child_nodes()
    {
        $count_child = 0;
        $children = $this->tag[0];
        $child = $children->child_nodes();
        foreach ($child as $value) {
            $children->remove_child($child[$count_child]);
            $count_child++;
        }
    }

    function clear_child_nodes_by_name($name)
    {
        $count_child = 0;
        $children = $this->tag[0];
        $child = $children->child_nodes();
        foreach ($child as $value) {
            if ($value->node_name() == $name || $value->node_name() == '#text') {
                $children->remove_child($child[$count_child]);
            }
            $count_child++;
        }
    }

    function add_child_node($content, $param = false, $change_tree = false, $XML_TYPE = XML_TEXT_NODE)
    {
        $content = each($content);
        $node = $this->xml->create_element($content["key"]);

        $new_Node = $this->tag->append_child($node);
        $node = $XML_TYPE == XML_CDATA_SECTION_NODE ? $this->xml->create_cdata_section(iconv("Windows-1251", "UTF-8", $content["value"])) : $this->xml->create_text_node(iconv("Windows-1251", "UTF-8", $content["value"]));
        $new_Node->append_child($node);

        if (!empty($param)) {
            foreach ($param as $key => $value)
                $new_Node->set_attribute($key, iconv("Windows-1251", "UTF-8", $value));
        }
        if ($change_tree)
            $this->tag = &$new_Node;
    }

    public function appendXML(DOMDocument $xml)
    {
        if (empty($xml->documentElement))
            return false;
        $node = $this->xml->importNode($xml->documentElement, true);

        if ($this->subroot) {
            $this->subroot->appendChild($node);
        } else {
            $this->root->appendChild($node);
        }
    }

}

?>
