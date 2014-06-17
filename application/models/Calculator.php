<?php
class Calculator extends Core_Connect
{
    protected $_name = 'CALCULATOR';

    public function getCalculators()
    {
        $sql = "select *
                from {$this->_name}
                where STATUS = 1
                order by ORDER_";

        return $this->_db->fetchAll($sql);
    }

    public function getCalculatorIdByIndent($indent)
    {
        $sql = "select CALCULATOR_ID
                from {$this->_name}
                where STATUS = 1
                  and INDENT = ?";

        return $this->_db->fetchOne($sql, array($indent));
    }

    public function getFirstCalculatorId()
    {
        $sql = "select CALCULATOR_ID
                from {$this->_name}
                where STATUS = 1
                order by ORDER_
                limit 1";

        return $this->_db->fetchOne($sql);
    }

    public function getCalculator($id)
    {
        $sql = "select *
                from {$this->_name}
                where STATUS = 1
                  and CALCULATOR_ID = ?";

        return $this->_db->fetchRow($sql, array($id));
    }
}