<?php
class Vopros extends Core_Connect
{

    function getVopros($lang)
    {
        $now = date("Y-m-d");

        if ($lang > 0) {
            $sql = "select A.VOPROS_ID
                   ,A.COUNT_
                   ,B.NAME
             from VOPROS A inner join VOPROS_LANGS B on (A.VOPROS_ID = B.VOPROS_ID)
             where A.DATA_START <= '{$now} 23:59:59'
               and A.DATA_STOP  >= '{$now} 23:59:59'
               and A.STATUS = 1
               and B.CMF_LANG_ID= {$lang}";
        } else {
            $sql = "select *
             from VOPROS
             where DATA_START <= '{$now} 23:59:59'
               and DATA_STOP  >= '{$now} 23:59:59'
               and STATUS = 1";
        }

        return $this->_db->fetchRow($sql);
    }

    function getAllVopros($lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.VOPROS_ID
                   ,A.COUNT_
                   ,date_format(A.DATA_START, '%d.%m.%Y') as data_start_result
                   ,date_format(A.DATA_STOP, '%d.%m.%Y') as data_stop_result
                   ,A.DATA_STOP
                   ,B.NAME
             from VOPROS A inner join VOPROS_LANGS B on (A.VOPROS_ID = B.VOPROS_ID)
             where A.STATUS = 1
               and B.CMF_LANG_ID= {$lang}
             order by A.DATA_STOP desc";
        } else {
            $sql = "select *
                   ,date_format(DATA_START, '%d.%m.%Y') as data_start_result
                   ,date_format(DATA_STOP, '%d.%m.%Y') as data_stop_result
             from VOPROS
             where STATUS = 1
             order by DATA_STOP desc";
        }


        return $this->_db->fetchAll($sql);
    }

    function getOtvets($vopros_id, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.OTVETS_ID
                   ,B.NAME
                   ,A.COUNT_
             from OTVETS A inner join OTVETS_LANGS B on (A.OTVETS_ID = B.OTVETS_ID)
             where A.VOPROS_ID = {$vopros_id}
               and B.CMF_LANG_ID={$lang}
             order by A.ORDERING";
        } else {
            $sql = "select *
             from OTVETS
             where VOPROS_ID = {$vopros_id}
             order by ORDERING";
        }

        return $this->_db->fetchAll($sql);
    }

    function getMaxOtvet($vopros_id)
    {
        $sql = "select max(COUNT_)
             from OTVETS
             where VOPROS_ID = {$vopros_id}
             group by VOPROS_ID";

        return $this->_db->fetchOne($sql);
    }

    function getClientVopros($id)
    {
        $sql = "select CV.*
           from CLIENT_VOPROS CV
              , CLIENT_VOPROS_CLIENTS CVC
           where CVC.CLIENT_HASH = '{$id}'
             and CV.CLIENT_VOPROS_ID = CVC.CLIENT_VOPROS_ID
             and CVC.STATUS = 1";

        return $this->_db->fetchRow($sql);
    }

    function getClientVoprosClient($id)
    {
        $sql = "select STATUS
           from CLIENT_VOPROS_CLIENTS
           where CLIENT_HASH = '{$id}'";

        return $this->_db->fetchOne($sql);
    }

    function getClientOtvets($vopros_id)
    {
        $sql = "select *
           from CLIENT_OTVETS
           where CLIENT_VOPROS_ID = {$vopros_id}
           order by ORDERING";

        return $this->_db->fetchAll($sql);
    }

    function setClientVopros($id)
    {
        $sql = "update CLIENT_VOPROS_CLIENTS
           set STATUS = 0
           where CLIENT_HASH = '{$id}'";

        $this->_db->query($sql);
    }

    function getVoprosID($id)
    {
        $sql = "select VOPROS_ID
           from OTVETS
           WHERE OTVETS_ID = {$id}";

        return $this->_db->fetchOne($sql);
    }

    function voprosUp($id)
    {
        $sql = "update VOPROS
           set  COUNT_ = COUNT_ + 1
           WHERE VOPROS_ID = {$id}";

        $this->_db->query($sql);
    }

    function otvetUp($id)
    {
        $sql = "update OTVETS
           set  COUNT_ = COUNT_ + 1
           WHERE OTVETS_ID = {$id}";

        $this->_db->query($sql);
    }

}

?>
