<?php

class Faq extends ZendDBEntity
{

    protected $_name = 'QUESTION';

    public function getGroupMessage($lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.QUESTION_GROUP_ID
                 , B.NAME
            from QUESTION_GROUP A inner join QUESTION_GROUP_LANGS B on B.QUESTION_GROUP_ID=A.QUESTION_GROUP_ID
            where A.STATUS = 1
            order by A.ORDERING";
        } else {
            $sql = "select *
            from QUESTION_GROUP
            where STATUS = 1
            order by ORDERING";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getMessage($id, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.QUESTION_ID
                 , B.QUESTION
                 , B.ANSWER
            from QUESTION A inner join QUESTION_LANGS B on B.QUESTION_ID=A.QUESTION_ID
            where A.STATUS = 1
              AND A.QUESTION_GROUP_ID = ?
            order by A.ORDERING";
        } else {
            $sql = "select *
            from QUESTION
            where STATUS = 1
              AND QUESTION_GROUP_ID = ?
            order by ORDERING";
        }

        return $this->_db->fetchAll($sql, $id);
    }

}

?>
