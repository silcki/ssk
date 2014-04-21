<?php
class HelperSystemTextes extends Core_Controller_Action_Helper_Abstract
{
    protected static $systemTextes = array(
        'search_text',
        'image_map',
        'text_zakaz_callback',
        'text_callback_callback',
        'text_callback_sendyournumber',
        'text_callback_name',
        'text_callback_phone',
        'text_callback_timeforcall',
        'text_callback_message',
        'text_callback_send',
        'text_callback_from',
        'text_callback_till',
        'text_callback_ticket',
        'text_complain_ticket',
        'text_zakaz_phone',
        'text_complain_name',
        'text_complain_phone',
        'text_complain_email',
        'text_complain_message',
        'text_complain_send',
        'item_catalog',
    );

    /**
     * @var Textes
     */
    protected $textes;

    public function init()
    {
        $this->textes = $this->getServiceManager()->getModel()->getTextes();
    }

    /**
     * Создать узлы системных текстов
     */
    public function getTextes()
    {
        foreach (self::$systemTextes as $indent) {
            $this->getSysText($indent);
        }
    }

    /**
     * Создать узел системного текста
     *
     * @param string $indent идентификатор
     */
    public function getSysText($indent)
    {
        $textes = $this->textes->getSysText($indent, $this->params['langId']);

        if (!empty($textes['DESCRIPTION']) || !empty($textes['IMAGE'])) {
            $description = $textes['DESCRIPTION'];
            $this->domXml->set_tag('//page', true);
            $this->domXml->create_element($indent, $description, 2);
            if (!empty($textes['IMAGE']) && strchr($textes['IMAGE'], "#")) {
                $tmp = explode('#', $textes['IMAGE']);
                $this->domXml->set_attribute(array('src' => $tmp[0],
                    'w' => $tmp[1],
                    'h' => $tmp[2]
                ));
            }
            $this->domXml->go_to_parent();
        }
    }
} 