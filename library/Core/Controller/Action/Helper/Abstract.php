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

    /**
     * @param int  $id    ID записи
     * @param int  $type  тип записи
     * @param bool $tag   импортировать ли с тегом
     * @param int  $lang  язык
     * @param bool $cdata делать импорт как CDATA
     */
    public function getDocXml($id = 0, $type = 0, $tag = false, $lang = 0)
    {
        $doc = $this->getServiceManager()->getModel()->getAnotherPages()->getDocXml($id, $type, $lang);
        $doc = stripslashes($doc);

        if (!empty($doc)) {
            $pattern = '/<a(.+)href="([http:\/\/|https:\/\/]?.+)"/Uis';
            $doc = preg_replace_callback($pattern, array($this, 'addSizeText'), $doc);

            $pattern = '/(<a.*href=".*\.(\w+)) size=(.+)"/Uis';
            $doc = preg_replace($pattern, '${1}" typeDoc="$2" size="$3"', $doc);

            $txt = "<?xml version=\"1.0\" encoding=\"{$this->domXml->get_encoding()}\"?><!DOCTYPE stylesheet SYSTEM \"symbols.ent\">";
            if ($tag) {
                $txt.= "<txt>" . $doc . "</txt>";
            } else {
                $txt.= $doc;
            }

            $this->domXml->import_node($txt);
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

    /**
     * Создать ссылку на коренб сайта в бредкрамбе
     */
    public function getRootPath()
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

    /**
     * @param array $beforPath
     */
    protected function getBeforPath($beforPath)
    {
        if (empty($beforPath) || !is_array($beforPath)) {
            return false;
        }

        foreach ($beforPath as $view) {
            $this->domXml->create_element('breadcrumbs', '', 2);
            $this->domXml->set_attribute(array(
                'id' => 0 ,
                'parent_id' => 0
            ));

            $href = $view['url'];

            $this->domXml->create_element('name', $view['name']);
            $this->domXml->create_element('url', $href);
            $this->domXml->go_to_parent();
        }
    }

    /**
     * @param array $afterPath
     */
    protected function getAfterPath($afterPath)
    {
        if (empty($afterPath) || !is_array($afterPath)) {
            return false;
        }

        foreach ($afterPath as $view) {
            $this->domXml->create_element('breadcrumbs', '', 2);
            $this->domXml->set_attribute(array(
                'id' => 0 ,
                'parent_id' => 0
            ));

            $href = '/' . $view['url'];

            $this->domXml->create_element('name', $view['name']);
            $this->domXml->create_element('url', $href);
            $this->domXml->go_to_parent();
        }
    }

    public function getSettingValue($name)
    {
        return $this->getServiceManager()->getModel()->getSystemSets()->getSettingValue($name);
    }

    protected function getMailTrasportData()
    {
        $mailTransportConfig['port'] = 25;
        $mailTransportConfig['auth'] = 'login';
        $mailTransportConfig['username'] = $this->getSettingValue('mail_transport_username');
        $mailTransportConfig['password'] = $this->getSettingValue('mail_transport_password');
        $mailTransportConfig['host'] = $this->getSettingValue('mail_transport_host');

        return $mailTransportConfig;
    }
} 