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
}