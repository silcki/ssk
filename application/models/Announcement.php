<?php

class Announcement extends ZendDBEntity
{

    protected $_name = 'ANNOUNCEMENT';

    public function getAnnouncementCount($rubrics_id = 0, $types_id = 0)
    {
        $where = "";
        if (!empty($rubrics_id))
            $where .= " and ANNOUNCEMENT_RUBRICS_ID = {$rubrics_id}";
        if (!empty($types_id))
            $where .= " and ANNOUNCEMENT_TYPES_ID = {$types_id}";
        $sql = "select count(*)
          from ANNOUNCEMENT
          where STATUS = 1
          {$where}
          order by DATE desc";

        return $this->_db->fetchOne($sql);
    }

    public function getAnnouncement($rubrics_id = 0, $types_id = 0,
                                    $startSelect = 0, $announcement_per_page = 0)
    {
        $where = "";
        if (!empty($rubrics_id))
            $where .= " and A.ANNOUNCEMENT_RUBRICS_ID = {$rubrics_id}";
        if (!empty($types_id))
            $where .= " and A.ANNOUNCEMENT_TYPES_ID = {$types_id}";

        $sql = "select A.*
              , DATE_FORMAT(A.DATE,'%d.%m.%Y') as ndate
              , AR.NAME as AR_NAME
              , AT.NAME as AT_NAME
          from ANNOUNCEMENT A
          join ANNOUNCEMENT_RUBRICS AR on (AR.ANNOUNCEMENT_RUBRICS_ID = A.ANNOUNCEMENT_RUBRICS_ID)
          join ANNOUNCEMENT_TYPES AT on (AT.ANNOUNCEMENT_TYPES_ID = A.ANNOUNCEMENT_TYPES_ID)
          where A.STATUS = 1
          {$where}
          order by A.DATE desc
          limit {$startSelect}, {$announcement_per_page}";

        return $this->_db->fetchAll($sql);
    }

    public function getAnnouncementInfo($id)
    {
        $sql = "select A.*
              , DATE_FORMAT(A.DATE,'%d.%m.%Y') as ndate
              , AR.NAME as AR_NAME
              , AT.NAME as AT_NAME
          from ANNOUNCEMENT A
          join ANNOUNCEMENT_RUBRICS AR on (AR.ANNOUNCEMENT_RUBRICS_ID = A.ANNOUNCEMENT_RUBRICS_ID)
          join ANNOUNCEMENT_TYPES AT on (AT.ANNOUNCEMENT_TYPES_ID = A.ANNOUNCEMENT_TYPES_ID)
          where A.ANNOUNCEMENT_ID = {$id}";

        return $this->_db->fetchRow($sql);
    }

    public function getRubrics($lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.ANNOUNCEMENT_RUBRICS_ID
                 , B.NAME
            from ANNOUNCEMENT_RUBRICS A inner join ANNOUNCEMENT_RUBRICS_LANGS B on B.ANNOUNCEMENT_RUBRICS_ID=A.ANNOUNCEMENT_RUBRICS_ID
            where A.STATUS = 1
            order by DATA desc";
        } else {
            $sql = "select *
            from ANNOUNCEMENT_RUBRICS
            where STATUS = 1
            order by NAME";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getTypes($lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.ANNOUNCEMENT_TYPES_ID
                 , B.NAME
            from ANNOUNCEMENT_TYPES A inner join ANNOUNCEMENT_TYPES_LANGS B on B.ANNOUNCEMENT_TYPES_ID=A.ANNOUNCEMENT_TYPES_ID
            where A.STATUS = 1
            order by NAME";
        } else {
            $sql = "select *
            from ANNOUNCEMENT_TYPES
            where STATUS = 1
            order by NAME";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getWorkRubrics($types_id, $lang = 0)
    {
        $where = "";
        if (!empty($types_id))
            $where .= " and AN.ANNOUNCEMENT_TYPES_ID = {$types_id}";

        if ($lang > 0) {
            $sql = "select A.ANNOUNCEMENT_RUBRICS_ID
                 , B.NAME
            from ANNOUNCEMENT_RUBRICS A inner join ANNOUNCEMENT_RUBRICS_LANGS B on B.ANNOUNCEMENT_RUBRICS_ID=A.ANNOUNCEMENT_RUBRICS_ID
            join ANNOUNCEMENT AN on (AN.ANNOUNCEMENT_RUBRICS_ID = A.ANNOUNCEMENT_RUBRICS_ID) and AN.STATUS=1
            where A.STATUS = 1
            {$where}
            group by A.ANNOUNCEMENT_RUBRICS_ID
            order by DATA desc";
        } else {
            $sql = "select A.*
            from ANNOUNCEMENT_RUBRICS A
            join ANNOUNCEMENT AN on (AN.ANNOUNCEMENT_RUBRICS_ID = A.ANNOUNCEMENT_RUBRICS_ID) and AN.STATUS=1
            where A.STATUS = 1
            {$where}
            group by A.ANNOUNCEMENT_RUBRICS_ID
            order by A.NAME";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getWorkTypes($lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.ANNOUNCEMENT_TYPES_ID
                 , B.NAME
            from ANNOUNCEMENT_TYPES A inner join ANNOUNCEMENT_TYPES_LANGS B on B.ANNOUNCEMENT_TYPES_ID=A.ANNOUNCEMENT_TYPES_ID
            join ANNOUNCEMENT AN on (AN.ANNOUNCEMENT_TYPES_ID = A.ANNOUNCEMENT_TYPES_ID) and AN.STATUS=1
            where A.STATUS = 1
            group by A.ANNOUNCEMENT_TYPES_ID
            order by NAME";
        } else {
            $sql = "select A.*
            from ANNOUNCEMENT_TYPES A
            join ANNOUNCEMENT AN on (AN.ANNOUNCEMENT_TYPES_ID = A.ANNOUNCEMENT_TYPES_ID)
            where A.STATUS = 1
            group by A.ANNOUNCEMENT_TYPES_ID
            order by A.NAME";
        }

        return $this->_db->fetchAll($sql);
    }

    public function insertAnnouncement($data)
    {
        $this->_db->insert('ANNOUNCEMENT', $data);

        return $this->_db->lastInsertId();
    }

}

?>
