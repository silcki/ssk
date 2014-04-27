<?php
class Clients extends Core_Connect
{

    protected $_name = 'CLIENT';

    public function getClientsIndex($lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.CLIENT_ID
                 , B.NAME
                 , A.EMAIL
                 , A.URL
                 , A.IMAGE1
                 , B.DESCRIPTION
            from CLIENT A inner join CLIENT_LANGS B on B.CLIENT_ID=A.CLIENT_ID
            where A.STATUS = 1
              and A.STATUS_MAIN = 1
            order by ORDERING";
        } else {
            $sql = "select *
            from CLIENT
            where STATUS = 1
              and STATUS_MAIN = 1
            order by ORDERING";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getClients($lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.CLIENT_ID
                 , B.NAME
                 , A.EMAIL
                 , A.URL
                 , A.IMAGE1
                 , B.DESCRIPTION
            from CLIENT A inner join CLIENT_LANGS B on B.CLIENT_ID=A.CLIENT_ID
            where A.STATUS = 1
            order by ORDERING";
        } else {
            $sql = "select *
            from CLIENT
            where STATUS = 1
            order by ORDERING";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getClientsInfo($id, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.CLIENT_ID
                 , B.NAME
                 , B.DESCRIPTION
            from CLIENT A inner join CLIENT_LANGS B on B.CLIENT_ID=A.CLIENT_ID
            where A.STATUS = 1
              and A.STATUS_MAIN = 1
              and A.CLIENT_ID = {$id}
            order by ORDERING";
        } else {
            $sql = "select CLIENT_ID
                 , NAME
                 , DESCRIPTION
            from CLIENT
            where STATUS = 1
              and STATUS_MAIN = 1
              and CLIENT_ID = {$id}
            order by ORDERING";
        }

        return $this->_db->fetchRow($sql);
    }

    public function getCountry()
    {
        $sql = "select C.*
                from COUNTRY C
                inner join CLIENT CL on (Cl.COUNTRY_ID = C.COUNTRY_ID)
                where C.STATUS = 1
                order by C.NAME";

        return $this->_db->fetchAll($sql);
    }

    public function getScope()
    {
        $sql = "select S.*
                from SCOPE S
                inner join CLIENT CL on (Cl.SCOPE_ID = S.SCOPE_ID)
                where S.STATUS = 1
                order by S.NAME";

        return $this->_db->fetchAll($sql);
    }

    public function getProductType()
    {
        $sql = "select PT.*
                from PRODUCT_TYPE PT
                inner join CLIENT_PRODUCT_TYPE CPT on (CPT.PRODUCT_TYPE_ID = PT.PRODUCT_TYPE_ID)
                where PT.STATUS = 1
                order by PT.NAME";

        return $this->_db->fetchAll($sql);
    }

}