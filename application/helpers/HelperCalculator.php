<?php
class HelperCalculator extends Core_Controller_Action_Helper_Abstract
{
    /**
     * @var Calculator
     */
    protected $calculator;

    public function init()
    {
        $this->calculator = $this->getServiceManager()->getModel()->getCalculator();
    }

    /**
     * @return $this
     */
    public function getCalculators()
    {
        $this->domXml->set_tag('//data', true);
        $calculator = $this->calculator->getCalculators();
        if (!empty($calculator)) {
            foreach ($calculator as $view) {
                $this->domXml->create_element('calculator', '', 2);
                $this->domXml->set_attribute(array('id' => $view['CALCULATOR_ID']));

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('indent', $view['INDENT']);

                $doc = $this->getServiceManager()->getModel()->getAnotherPages()->getDocXml($view['CALCULATOR_ID'], 12, 0);
                $doc = stripslashes($doc);

                $this->domXml->create_element('txt', $doc, 3, array(), 1);

                if (!empty($view['IMAGE1']) && strchr($view['IMAGE1'], "#")) {
                    $tmp = explode('#', $view['IMAGE1']);
                    $this->domXml->create_element('image', '', 2);
                    $this->domXml->set_attribute(
                        array(
                            'src' => '/images/calc/'.$tmp[0],
                            'w' => $tmp[1],
                            'h' => $tmp[2]
                    ));
                    $this->domXml->go_to_parent();
                }

                $this->domXml->go_to_parent();
            }
        }

        return $this;
    }
} 