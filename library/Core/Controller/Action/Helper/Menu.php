<?php
class Core_Controller_Action_Helper_Menu
{
    private $domXml;

    public function __construct($domXml)
    {
        $this->domXml = $domXml;
    }

    /**
     * Создать узел для меню
     * @param string $path
     * @param string $attrName
     * @param type $attrValue
     */
    public function setNode($path, $attrName, $attrValue)
    {

        $xpath = new DOMXPath($this->domXml->getXMLobject());
        $entries = $xpath->query($path);
        if ($entries->length > 0) {
            foreach ($entries as $key => $value) {
                $value->removeAttribute((string) $attrName);
                $value->setAttribute((string) $attrName, (string) $attrValue);
            }
        }

        return $this->domXml;
    }

}