<?php
class SystemSets extends Core_Connect
{

    public function getSettingValue($where)
    {
        $sql = "select VALUE
          from SETINGS
          where SYSTEM_NAME='{$where}'";

        return $this->_db->fetchOne($sql);
    }

}