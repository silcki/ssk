<?php
class HelperCalculator extends Core_Controller_Action_Helper_Abstract
{
    /**
     * @var Calculator
     */
    protected $calculator;

    /**
     * @var int
     */
    private $_calculatorId;

    public function init()
    {
        $this->calculator = $this->getServiceManager()->getModel()->getCalculator();
    }

    /**
     * @param string|null $indent
     *
     * @return $this
     */
    public function getCalculatorIdByIndent($indent)
    {
        if (empty($indent)) {
            $this->_calculatorId = $this->calculator->getFirstCalculatorId();
        } else {
            $this->_calculatorId = $this->calculator->getCalculatorIdByIndent($indent);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getCalculatorId()
    {
        return $this->_calculatorId;
    }

    /**
     * @param string $sectionUrl
     *
     * @return $this
     */
    public function getCalculatorsTabs($sectionUrl)
    {
        $this->domXml->set_tag('//data', true);
        $calculator = $this->calculator->getCalculators();
        if (!empty($calculator)) {
            foreach ($calculator as $view) {
                $this->domXml->create_element('calculator_tabs', '', 2);
                $this->domXml->set_attribute(
                    array(
                        'id' => $view['CALCULATOR_ID'],
                        'active' => ($this->_calculatorId == $view['CALCULATOR_ID']) ? 1:0,
                    )
                );

                $url = $sectionUrl.$view['INDENT'].'/';

                $this->domXml->create_element('name', $view['NAME']);
                $this->domXml->create_element('url', $url);

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

    /**
     * @param string $indent
     *
     * @return $this
     */
    public function getCalculator()
    {
        if (empty($this->_calculatorId)) {
            return false;
        }

        $this->domXml->set_tag('//data', true);
        $calculator = $this->calculator->getCalculator($this->_calculatorId);
        if (!empty($calculator)) {
            $this->domXml->create_element('calculator', '', 2);
            $this->domXml->set_attribute(array('id' => $calculator['CALCULATOR_ID']));

            $this->domXml->create_element('name', $calculator['NAME']);

            $doc = $this->getServiceManager()->getModel()->getAnotherPages()->getDocXml($calculator['CALCULATOR_ID'], 12, 0);
            $doc = stripslashes($doc);

            $this->domXml->create_element('txt', $doc, 3, array(), 1);

            $this->domXml->go_to_parent();
        }

        return $this;
    }
} 