<?php
class Core_Controller_Action_Helper_Abstract
{
    /**
     * @var Core_ServiceManager
     */
    protected $serviceManager;

    /**
     * @var
     */
    protected $domXml;

    /**
     * @var array
     */
    protected $params;

    public function __construct()
    {
        $this->serviceManager = \Zend_Registry::get('serviceManager');
        $bootstrap = Zend_Controller_Front::getInstance()->getParam('bootstrap');

        $view = $bootstrap->getResource('view');
        $this->domXml = $view->getDomXml();
        $this->init();
    }

    public function init() {}

    /**
     * @return Core_ServiceManager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * @param $params
     *
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Метод для получения информации о картинке
     *
     * @access   public
     * @param    string $stringImage
     * @param    string $delimiter
     * @return   array  $Out
     */
    protected function splitImageProperties($stringImage, $delimiter = "#")
    {
        if (!$stringImage) {
            return null;
        }

        $tmp = explode($delimiter, $stringImage);
        $out[0]['src'] = $tmp[0];
        $out[0]['w'] = $tmp[1];
        $out[0]['h'] = $tmp[2];

        return $out;
    }

    public function setXmlNode($doc, $tag = '')
    {
        if (!empty($doc)) {
            $pattern = '/<a(.+)href="([http:\/\/|https:\/\/]?.+)"/Uis';
            $doc = preg_replace_callback($pattern, array($this, 'addSizeText'), $doc);

            $pattern = '/(<a.*href=".*\.(\w+)) size=(.+)"/Uis';
            $doc = preg_replace($pattern, '${1}" typeDoc="$2" size="$3"', $doc);

            if ($tag) {
                $txt = "<?xml version=\"1.0\" encoding=\"{$this->domXml->get_encoding()}\"?><!DOCTYPE stylesheet SYSTEM \"symbols.ent\"><$tag>" . $doc . "</$tag>";
            } else {
                $txt = "<?xml version=\"1.0\" encoding=\"{$this->domXml->get_encoding()}\"?><!DOCTYPE stylesheet SYSTEM \"symbols.ent\">" . $doc;
            }
            $this->domXml->import_node($txt, false);
        }
    }

    public function getDocXml($id = 0, $type = 0, $tag = false, $lang = 0)
    {
        $doc = $this->getServiceManager()->getModel()->getAnotherPages()->getDocXml($id, $type, $lang);
        $doc = stripslashes($doc);

        if (!empty($doc)) {
            $pattern = '/<a(.+)href="([http:\/\/|https:\/\/]?.+)"/Uis';
            $doc = preg_replace_callback($pattern, array($this, 'addSizeText'), $doc);

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

    public static  function convertSize($size)
    {
        $unit = array('b', 'Kb', 'Mb', 'Gb', 'Tb', 'Pb', 'Eb', 'Zb', 'Yb');
        return round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . $unit[$i];
    }

    public static function addSizeText($matches)
    {
        $_temp = $_SERVER['DOCUMENT_ROOT'] . $matches[2];
        if (is_file($_temp)) {
            $size = self::convertSize(filesize($_temp));

            return "<a " . $matches[1] . " href=\"" . $matches[2] . " size={$size}\"";
        } else {
            if (strpos($matches[2], 'http') !== false) {
                if (strpos($matches[2], 'redirect') !== false) {
                    return "<a " . $matches[1] . " href=\"" . urlencode($matches[2]) . "\"";
                }
            }
        }

        return "<a " . $matches[1] . " href=\"" . $matches[2] . "\"";
    }

    protected  function getRootPath()
    {
        $lang = '';
        if ($this->params['langId'] > 0) {
            $lang = '/' . $this->params['lang'];
        }

        $this->domXml->create_element('breadcrumbs', '', 2);
        $this->domXml->set_attribute(array('id' => 9
        , 'parent_id' => 0
        ));

        $href = $lang . '/';

        $textes = $this->getServiceManager()->getModel()->getTextes()->getSysText('page_main', $this->params['langId']);

        $this->domXml->create_element('name', $textes['DESCRIPTION']);
        $this->domXml->create_element('url', $href);
        $this->domXml->go_to_parent();
    }
} 