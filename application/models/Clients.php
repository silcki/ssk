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

    /**
     * @param int   $lang
     * @param array $whereParams
     *
     * @return array
     */
    public function getClients($lang = 0, $whereParams)
    {
        $joinWhere = $where = '';

        if (!empty($whereParams['countryId'])) {
            $where.= ' and A.COUNTRY_ID = ' .$whereParams['countryId'];
        }

        if (!empty($whereParams['scopeId'])) {
            $where.= ' and A.SCOPE_ID = ' .$whereParams['scopeId'];
        }

        if (!empty($whereParams['productTypeId'])) {
            $where.= ' and CPT.PRODUCT_TYPE_ID = ' .$whereParams['productTypeId'];
        }

        switch ($whereParams['order']) {
            case 'order':
                $orderBy = 'A.ORDERING asc';
                break;

            case 'name':
                $orderBy = 'A.NAME '.$whereParams['asc'];
                break;
        }

        if ($lang > 0) {
            $sql = "select A.CLIENT_ID
                         , B.NAME
                         , A.EMAIL
                         , A.URL
                         , A.IMAGE1
                         , B.DESCRIPTION
                    from CLIENT A inner join CLIENT_LANGS B on B.CLIENT_ID=A.CLIENT_ID
                    where A.STATUS = 1
                    {$where}
                    group by A.CLIENT_ID
                    order by {$orderBy}";
        } else {
            $sql = "select A.*
                    from CLIENT A
                    left join  CLIENT_PRODUCT_TYPE CPT ON (CPT.CLIENT_ID = A.CLIENT_ID) {$joinWhere}
                    where A.STATUS = 1
                    {$where}
                    group by A.CLIENT_ID
                    order by {$orderBy}";
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
                inner join CLIENT CL on (CL.COUNTRY_ID = C.COUNTRY_ID)
                where C.STATUS = 1
                group by C.COUNTRY_ID
                order by C.NAME";

        return $this->_db->fetchAll($sql);
    }

    public function getScope()
    {
        $sql = "select S.*
                from SCOPE S
                inner join CLIENT CL on (CL.SCOPE_ID = S.SCOPE_ID)
                where S.STATUS = 1
                group by S.SCOPE_ID
                order by S.NAME";

        return $this->_db->fetchAll($sql);
    }

    public function getProductType()
    {
        $sql = "select PT.*
                from PRODUCT_TYPE PT
                inner join CLIENT_PRODUCT_TYPE CPT on (CPT.PRODUCT_TYPE_ID = PT.PRODUCT_TYPE_ID)
                where PT.STATUS = 1
                group by PT.PRODUCT_TYPE_ID
                order by PT.NAME";

        return $this->_db->fetchAll($sql);
    }

}