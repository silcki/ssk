<?php
class FileTypes extends Core_Connect
{
    protected $_name = 'FILE_TYPES';

    public function getFileTypesInfo($where)
    {
        $sql = "select IMAGE1
          from FILE_TYPES
          where EXT='{$where}'";

        return $this->_db->fetchOne($sql);
    }

    public function getFileTypes()
    {
        $sql = "select *
          from FILE_TYPES";

        return $this->_db->fetchAll($sql);
    }

}

?>