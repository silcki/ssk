<?php
class Booklets extends Core_Connect
{
    protected $_name = 'BOOKLETS';

    public function getBooklets($lang = 0)
    {
        if ($lang > 0) {

        } else {
            $sql = "select *
                    from {$this->_name}
                    where STATUS = 1
                    order by ORDERING";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getBookletsPages($id, $lang = 0)
    {
        if ($lang > 0) {
        } else {
            $sql = "select *
                    from BOOKLETS_PAGES
                    where STATUS = 1
                      and BOOKLETS_ID = ?
                    order by ORDERING";
        }

        return $this->_db->fetchAll($sql, $id);
    }

}