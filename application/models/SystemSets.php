<?php
class SystemSets extends Core_Connect
{

    /**
     * @param $name
     * @param $default
     *
     * @return mixed
     */
    public function getSettingValue($name, $default)
    {
        $sql = "select VALUE
                from SETINGS
                where SYSTEM_NAME = ?";

        $result = $this->_db->fetchOne($sql, array($name));

        return  $result ? $result:$default;
    }

}