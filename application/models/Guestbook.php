<?php

class Guestbook extends ZendDBEntity
{

    protected $_name = 'GUESTBOOK';

    public function insertMessage($data)
    {
        $this->_db->insert('GUESTBOOK', $data);

        return $this->_db->lastInsertId();
    }

    public function getMessage()
    {
        $sql = "select *
               , DATE_FORMAT(POSTED_AT,'%d/%m/%Y') as date
          from GUESTBOOK
          where STATUS = 1";

        return $this->_db->fetchAll($sql);
    }

}

?>
