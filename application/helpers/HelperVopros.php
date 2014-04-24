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
            $this->domXml->set_attribute(array(
                'id' => $vopros['VOPROS_ID'],
                'count' => $vopros['COUNT_']
            ));

            $this->domXml->create_element('name', $vopros['NAME']);

            $otvets = $this->vopros->getOtvets($vopros['VOPROS_ID'], $this->params['langId']);
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

        return $this;
    }

    public function getAllVote()
    {
        $all_vopros = $this->vopros->getAllVopros($this->params['langId']);
        if (!empty($all_vopros)) {
            $this->domXml->set_tag('//data', true);
            foreach ($all_vopros as $view) {
                $this->domXml->create_element('resultvopros', '', 2);
                $this->domXml->set_attribute(array(
                    'id' => $view['VOPROS_ID'],
                    'count' => $view['COUNT_']
                ));

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('data_start', $view['data_start_result']);
                $this->domXml->create_element('data_stop', $view['data_stop_result']);

                $otvets = $this->vopros->getOtvets($view['VOPROS_ID'], $this->params['langId']);
                $max_otvet_id = $this->vopros->getMaxOtvet($view['VOPROS_ID'], $this->params['langId']);

                if (!empty($otvets)) {
                    foreach ($otvets as $otvetsview) {
                        $this->domXml->create_element('resultotvets', '', 2);

                        if ($view['COUNT_'] > 0)
                            $percent = round(($otvetsview['COUNT_'] * 100) / $view['COUNT_'],
                                2);
                        else
                            $percent = 0;

                        if ($max_otvet_id == $otvetsview['COUNT_'])
                            $is_max = 1;
                        else
                            $is_max = 0;

                        $this->domXml->set_attribute(array(
                            'id' => $otvetsview['OTVETS_ID'],
                            'percent' => $percent,
                            'count' => $otvetsview['COUNT_'],
                            'is_max' => $is_max
                        ));

                        $this->domXml->create_element('name', $otvetsview['NAME']);
                        $this->domXml->go_to_parent();
                    }
                }

                $this->domXml->go_to_parent();
            }
        }

        return $this;
    }
} 