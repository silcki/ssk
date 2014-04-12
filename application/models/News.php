<?php

class News extends ZendDBEntity
{

    public function getNewsIndexCount($amount)
    {
        $sql = "select count(*)
          from NEWS
          where STATUS = 1
          order by DATA desc
          limit {$amount}";

        return $this->_db->fetchOne($sql);
    }

    public function getNewsGroups($lang)
    {
        if ($lang > 0) {
            $sql = "select NGL.*
            from NEWS_GROUP B inner join NEWS_GROUP_LANGS NGL on NGL.NEWS_GROUP_ID=B.NEWS_GROUP_ID
            join NEWS N on (N.NEWS_GROUP_ID = B.NEWS_GROUP_ID)
            where NGL.CMF_LANG_ID = {$lang}
              and N.STATUS = 1
            group by B.NEWS_GROUP_ID
            order by B.ORDERING";
        } else {
            $sql = "select NG.*
            from NEWS_GROUP NG
            join NEWS N on (N.NEWS_GROUP_ID = NG.NEWS_GROUP_ID)
            where N.STATUS = 1
            group by NG.NEWS_GROUP_ID
            order by NG.ORDERING";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getNewsCount($groupId)
    {
        $where = '';
        if (!empty($groupId))
            $where = " and NEWS_GROUP_ID={$groupId} ";

        $sql = "select count(*)
          from NEWS
          where STATUS = 1
          {$where}";

        return $this->_db->fetchOne($sql);
    }
    
    public function getSiteMapNews()
    {
        $sql = "select NEWS_ID
                      ,NAME
                      ,DESCRIPTION as descript
                      ,URL
                from NEWS
                where STATUS=1
                order by DATA desc";

        return $this->_db->fetchAll($sql);
    }

    public function getNewsIndex($groupId = 0, $lang = 0, $pageSize = 3)
    {
        if ($pageSize == 0) {
            $pageSize = 3;
        }

        if ($groupId > 0) {
            if ($lang > 0) {
                $sql = "select A.NEWS_ID
                         , DATE_FORMAT(A.DATA,'%d.%m.%Y') as date
                         , B.NAME
                         , A.URL
                         , A.IMAGE1
                         , B.DESCRIPTION as descript
                    from NEWS A inner join NEWS_LANGS B on B.NEWS_ID=A.NEWS_ID
                    where A.NEWS_GROUP_ID={$groupId}
                      and A.STATUS=1
                      and B.CMF_LANG_ID={$lang}
                    order by A.DATA desc
                    limit 0, {$pageSize}";
            } else {
                $sql = "select NEWS_ID
                         , NAME
                         , URL
                         , IMAGE1
                         , DATE_FORMAT(DATA,'%d.%m.%Y') as date
                         , DESCRIPTION as descript
                    from NEWS
                    where NEWS_GROUP_ID={$groupId}
                      and STATUS=1
                    order by DATA desc
                    limit 0, {$pageSize}";
            }
        } else {
            if ($lang > 0) {
                $sql = "select  A.NEWS_ID
                          , DATE_FORMAT(A.DATA,'%d.%m.%Y') as date
                          , B.NAME
                          , B.DESCRIPTION as descript
                          , A.URL
                          , A.IMAGE1
                    from NEWS A inner join NEWS_LANGS B on B.NEWS_ID=A.NEWS_ID
                    where A.STATUS=1
                      and B.CMF_LANG_ID= {$lang}
                    order by A.DATA desc
                    limit 0, {$pageSize}";
            } else {
                $sql = "select NEWS_ID
                          ,NAME
                          ,DATE_FORMAT(DATA,'%d.%m.%Y') as date
                          ,DESCRIPTION as descript
                          ,URL
                          ,IMAGE1
                    from NEWS
                    where STATUS=1
                    order by DATA desc
                    limit 0, {$pageSize}";
            }
        }

        $news = $this->_db->fetchAll($sql, $groupId);
        if ($news) {
            for ($i = 0; $i < sizeof($news); $i++) {
                if ($lang > 0)
                    $xml = $this->_db->fetchOne("select XML from XMLS where TYPE=1 and XMLS_ID=? and CMF_LANG_ID=?",
                                                array($news[$i]['NEWS_ID'], $lang));
                else
                    $xml = $this->_db->fetchOne("select XML from XMLS where TYPE=1 and XMLS_ID=?",
                                                array($news[$i]['NEWS_ID']));

                if ($xml) {
                    $news[$i]['has_xml'] = 1;
                } else {
                    $news[$i]['has_xml'] = 0;
                }
            }
        }

        return $news;
    }

    public function getNews($newsGroupId, $lang = 0, $startSelect = 0,
                            $newsPerPage = 0)
    {
        $limit = '';
        $where = '';
        if (!empty($newsGroupId))
            $where = " and A.NEWS_GROUP_ID = {$newsGroupId} ";
        if (!empty($newsPerPage))
            $limit = " limit {$startSelect}, {$newsPerPage}";
        if ($lang > 0) {
            $sql = "select A.NEWS_ID
                   , A.NEWS_GROUP_ID
                   , A.URL
                   , A.IMAGE1
                   , B.NAME
                   , DATE_FORMAT(A.DATA,'%d.%m.%Y') as date
                   , B.DESCRIPTION as descript
              from NEWS A inner join NEWS_LANGS B on B.NEWS_ID=A.NEWS_ID
              where A.STATUS=1
                {$where}
                and B.CMF_LANG_ID= {$lang}
              order by A.DATA desc
              {$limit}";
        } else {
            $sql = "select  A.NEWS_ID
                    , A.NEWS_GROUP_ID
                    , A.NAME
                    , A.URL
                    , A.IMAGE1
                    , DATE_FORMAT(A.DATA,'%d.%m.%Y') as date
                    , A.DESCRIPTION as descript
              from NEWS A
              where A.STATUS=1
                {$where}
              order by A.DATA desc
              {$limit}";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getNewsGroupSingle($id, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.NEWS_GROUP_ID
                 , B.NAME
            from NEWS_GROUP A inner join NEWS_GROUP_LANGS B on B.NEWS_GROUP_ID=A.NEWS_GROUP_ID
            where A.NEWS_GROUP_ID=?
              and B.CMF_LANG_ID= {$lang}";
        } else {
            $sql = "select NEWS_GROUP_ID
                 , NAME
            from NEWS_GROUP
            where NEWS_GROUP_ID=?";
        }

        return $this->_db->fetchRow($sql, array($id));
    }

    public function getNewsSingle($id, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.NEWS_ID
                 , B.NAME
                 , DATE_FORMAT(A.DATA,'%d.%m.%Y') as date
                 , A.NEWS_GROUP_ID
                 , A.URL
                 , A.IMAGE1
            from NEWS A inner join NEWS_LANGS B on B.NEWS_ID=A.NEWS_ID
            where A.NEWS_ID=?
              and B.CMF_LANG_ID= {$lang}";
        } else {
            $sql = "select NEWS_ID
                 , NAME
                 , DATE_FORMAT(DATA,'%d.%m.%Y') as date
                 , NEWS_GROUP_ID
                 , URL
                 , IMAGE1
            from NEWS
            where NEWS_ID=?";
        }

        return $this->_db->fetchRow($sql, $id);
    }

    public function searchNews($query, $lang = 0)
    {

        $where1 = " and B.NAME LIKE '%{$query}%' ";
        $where2 = " and B.NAME LIKE '%{$query}%' ";

        $query_arr = explode(' ', $query);
        if (count($query_arr) > 1) {
            foreach ($query_arr as $view) {
                $where2 .= " or B.NAME LIKE '%{$view}%' ";
            }
        }

        if ($lang > 0) {
            $sql = "select A.NEWS_ID
                  ,B.NAME
                  ,A.URL
                  ,A.IMAGE1
            from NEWS A inner join NEWS_LANGS B on B.NEWS_ID=A.NEWS_ID
            where B.CMF_LANG_ID= {$lang}
            {$where1}

            union

            select A.NEWS_ID
                  ,B.NAME
                  ,A.URL
                  ,A.IMAGE1
            from NEWS A inner join NEWS_LANGS B on B.NEWS_ID=A.NEWS_ID
            where B.CMF_LANG_ID= {$lang}
            {$where2}";
        } else {
            $sql = "select B.NEWS_ID
                  ,B.NAME
                  ,B.URL
                  ,B.IMAGE1
            from NEWS B
            where 1
            {$where1}

            union

            select B.NEWS_ID
                  ,B.NAME
                  ,B.URL
                  ,B.IMAGE1
            from NEWS B
            where 1
            {$where2}";
        }

        return $this->_db->fetchAll($sql);
    }

}