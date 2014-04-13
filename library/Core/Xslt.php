<?php
require_once(APPLICATION_PATH . '/../library/App/View/Serializer.php');
require_once(APPLICATION_PATH . '/../library/App/View/DOMxml.class.php');

class Core_Xslt extends Zend_View_Abstract
{
    /**
     * @var DomXML
     */
    private $_serializer;

    /**
     * Configuration
     *
     * @var array
     *
     */
    protected $_config = null;

    /**
     * Template in the view
     *
     * @var string
     */
    protected $_customTemplate = '';

    public function __construct($data = array())
    {
        $this->_config = $data;
        $this->_serializer = new Core_DomXML();

        parent::__construct($data);
    }

    /**
     * rewrite escape method
     *
     * @param mixed $var
     *
     * @return array|mixed
     */
    public function escape($var)
    {
        if (is_string($var)) {
            return parent::escape($var);
        } elseif (is_array($var)) {
            foreach ($var as $key => $val) {
                $var[$key] = $this->escape($val);
            }
        }
        return $var;
    }

    /**
     * Wrapper for render method
     *
     * @param $name
     */
    public function output($name)
    {
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        header("Cache-Control: post-check=0, pre-check=0", false);

        print($this->render($name));
    }

    /**
     * Get template file path
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->_customTemplate;
    }

    /**
     * Set template file path
     *
     * @param $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->_customTemplate = $template;
        return $this;
    }

    public function render($name)
    {
        $ext = strrchr($name, '.');
        if (!empty($ext)) {
            $ext = substr($ext, 1);
        }
        if ($this->_config['file_extension'] != $ext) {
            if ($ext != '.xsl') {
                $name = str_replace($ext, 'xsl', $name);
            }
        }

        return parent::render($name);
    }

    protected function _run()
    {
        $template = func_get_arg(0);
        $xslDoc = new DOMDocument();

        $xslDoc->load($template);

        $name = '_test.xml';

        $this->_serializer->saveXML($name);

        $xmlDoc = $this->toXml();

        $proc = new XSLTProcessor();
        $proc->importStylesheet($xslDoc);

        echo $proc->transformToXML($xmlDoc);
    }

    private function toXml()
    {
        return $this->_serializer->getXMLobject();
    }
}