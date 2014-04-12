<?php

class SystemSets extends ZendDBEntity
{

    public function getSettingValue($where)
    {
        $sql = "select VALUE
          from SETINGS
          where SYSTEM_NAME='{$where}'";

        return $this->_db->fetchOne($sql);
    }

}