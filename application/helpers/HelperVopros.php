<?php
class HelperVopros extends Core_Controller_Action_Helper_Abstract
{
    /**
     * @var Vopros
     */
    protected $vopros;

    public function init()
    {
        $this->vopros = $this->getServiceManager()->getModel()->getVopros();
    }

    public function getVopros($siteVote)
    {
        $this->domXml->create_element('site_vote', $siteVote, 2);
        $this->domXml->go_to_parent();

        $vopros = $this->vopros->getVopros($this->params['langId']);

        if (!empty($vopros)) {
            $this->domXml->set_tag('//page', true);
            $this->domXml->create_element('vopros', '', 2);
            $this->domXml->set_attribute(array('id' => $vopros['VOPROS_ID']
            , 'count' => $vopros['COUNT_']
            ));

            $this->domXml->create_element('name', $vopros['NAME']);

            $otvets = $this->vopros->getOtvets($vopros['VOPROS_ID'],
                $this->lang_id);
            if (!empty($otvets)) {
                foreach ($otvets as $view) {
                    $this->domXml->create_element('otvets', '', 2);
                    if ($vopros['COUNT_'] > 0)
                        $percent = round(($view['COUNT_'] * 100) / $vopros['COUNT_'],
                            2);
                    else
                        $percent = 0;

                    $this->domXml->set_attribute(array(
                        'id' => $view['OTVETS_ID'],
                        'percent' => $percent
                    ));

                    $this->domXml->create_element('name', $view['NAME']);
                    $this->domXml->go_to_parent();
                }
            }

            $this->domXml->go_to_parent();
        }
    }
} 