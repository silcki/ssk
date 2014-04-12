<?php
class Projects extends ZendDBEntity
{
    protected $_name = 'PROJECTS';

    public function getNewsIndexCount($amount)
    {
        $sql = "select count(*)
          from {$this->_name}
          where STATUS = 1
          order by DATA desc
          limit {$amount}";

        return $this->_db->fetchOne($sql);
    }

    public function getProjectsCount($groupId)
    {
        $sql = "select count(*)
                from {$this->_name}
                where STATUS = 1";

        return $this->_db->fetchOne($sql);
    }
    
    public function getSiteMapProjects()
    {
        $sql = "select PROJECTS_ID
                      ,NAME
                      ,DESCRIPTION as descript
                      ,URL
                from {$this->_name}
                where STATUS=1
                order by DATA desc";

        return $this->_db->fetchAll($sql);
    }

    public function getProjects($lang = 0, $startSelect = 0, $perPage = 0)
    {
        $limit = '';
        if (!empty($perPage)) {
            $limit = " limit {$startSelect}, {$perPage}";
        }

        if ($lang > 0) {
            $sql = "select A.PROJECTS_ID
                         , A.IMAGE1
                         , B.NAME
                         , DATE_FORMAT(A.DATA,'%d.%m.%Y') as date
                         , B.DESCRIPTION as descript
                    from {$this->_name} A inner join {$this->_name}_LANGS B on B.PROJECTS_ID=A.PROJECTS_ID
                    where A.STATUS=1
                      and B.CMF_LANG_ID= {$lang}
                    order by A.DATA desc
                      {$limit}";
        } else {
            $sql = "select  A.PROJECTS_ID
                          , A.NAME
                          , A.IMAGE1
                          , DATE_FORMAT(A.DATA,'%d.%m.%Y') as date
                          , A.DESCRIPTION as descript
                    from {$this->_name} A
                    where A.STATUS=1
                    order by A.DATA desc
                      {$limit}";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getProjectsSingle($id, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.PROJECTS_ID
                         , B.NAME
                         , DATE_FORMAT(A.DATA,'%d.%m.%Y') as date
                         , A.IMAGE1
                    from {$this->_name} A inner join {$this->_name}_LANGS B on B.PROJECTS_ID=A.PROJECTS_ID
                    where A.PROJECTS_ID=?
                      and B.CMF_LANG_ID= {$lang}";
        } else {
            $sql = "select PROJECTS_ID
                         , NAME
                         , DATE_FORMAT(DATA,'%d.%m.%Y') as date
                         , IMAGE1
                    from {$this->_name}
                    where PROJECTS_ID=?";
        }

        return $this->_db->fetchRow($sql, array($id));
    }
}