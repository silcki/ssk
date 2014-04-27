<?php
class HelperLanguages extends Core_Controller_Action_Helper_Abstract
{
    /**
     * @var AnotherPages
     */
    protected $anotherPages;

    public function init()
    {
        $this->anotherPages = $this->getServiceManager()->getModel()->getAnotherPages();
    }

    public function getLanguageInfo()
    {
        if ($this->params['lang'] != 'ru') {
            $lang_name = "/" . $this->params['lang'];
            $lang_sys_name = "/" . $this->params['lang'];
        } else {
            $lang_name = "";
            $lang_sys_name = "";
        }

        $this->domXml->create_element('lang', $this->params['lang'], 1);
        $this->domXml->create_element('lang_id', $this->params['langId'], 1);
        $this->domXml->create_element('lang_name', $lang_name, 1);
        $this->domXml->create_element('lang_sys_name', $lang_sys_name, 1);

        return $this;
    }

    /**
     * Метод для формирования списка языков системы
     * @access   public
     * @param
     * @param
     * @return  string xml
     */
    public function getLangs($uri)
    {
        $langs = $this->anotherPages->getLangs();
        foreach ($langs as $lang) {
            $this->domXml->create_element('langs', '', 1);
            $this->domXml->set_attribute(array(
                'cmf_lang_id' => $lang['CMF_LANG_ID'],
                'is_default' => $lang['IS_DEFAULT']
            ));

            $href = '';
            if ($this->params['langId'] > 0) {
                for ($i = 0; $i < count($uri) - 2; $i++) {
                    if (!empty($uri[$i]))
                        $href.= $uri[$i] . '/';
                }
            } else {
                for ($i = 0; $i < count($uri); $i++) {
                    if (!empty($uri[$i]))
                        $href.= $uri[$i] . '/';
                }
            }

            if ($lang['IS_DEFAULT'] == 0) {
                $href = '/' . $lang['SYSTEM_NAME'] . '/' . $href;
            } else {
                $href = '/' . $href;
            }

            $this->domXml->create_element('name', $lang['NAME']);
            $this->domXml->create_element('system_name', $lang['SYSTEM_NAME']);
            $this->domXml->create_element('url', $href);

            $this->domXml->go_to_parent();
        }

        return $this;
    }
} 