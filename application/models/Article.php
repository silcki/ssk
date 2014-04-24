<?php
class Article extends Core_Connect
{

    protected $_name = 'ARTICLE';

    public function articleGroup($lang = 0)
    {
        if ($lang > 0) {
            $sql = "select AGL.*
            from ARTICLE_GROUP B inner join ARTICLE_GROUP_LANGS AGL  on AGL.ARTICLE_GROUP_ID=B.ARTICLE_GROUP_ID
            join ARTICLE A on (A.ARTICLE_GROUP_ID = B.ARTICLE_GROUP_ID)
            where B.STATUS = 1
              and AGL.CMF_LANG_ID = {$lang}
            group by B.ARTICLE_GROUP_ID
            order by B.ORDERING";
        } else {
            $sql = "select AG.*
            from ARTICLE_GROUP AG
            join ARTICLE A on (A.ARTICLE_GROUP_ID = AG.ARTICLE_GROUP_ID)
            where AG.STATUS = 1
            group by AG.ARTICLE_GROUP_ID
            order by AG.ORDERING";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getArticleCount($article_group_id = 0)
    {
        $where = '';
        if (!empty($article_group_id))
            $where = " and ARTICLE_GROUP_ID = {$article_group_id} ";

        $sql = "select count(*)
          from ARTICLE
          where STATUS = 1
          {$where}";

        return $this->_db->fetchOne($sql);
    }
    
    public function getSiteMapArticle()
    {
        $sql = "select A.ARTICLE_ID
                     , A.URL
                     , A.NAME
                from ARTICLE A
                where A.STATUS=1
                order by A.DATA desc";
          
        return $this->_db->fetchAll($sql);
    }

    public function getArticles($article_group_id, $lang = 0, $startSelect, $article_per_page)
    {
        $limit = '';
        $where = '';
        if (!empty($article_group_id))
            $where = " and A.ARTICLE_GROUP_ID = {$article_group_id} ";
        if (!empty($article_per_page))
            $limit = " limit {$startSelect}, {$article_per_page}";
        if ($lang > 0) {
            $sql = "select A.ARTICLE_ID
                   , A.ARTICLE_GROUP_ID
                   , A.URL
                   , A.IMAGE1
                   , B.NAME
                   , date_format(A.DATA,'%d.%m.%Y') as date
                   , B.DESCRIPTION as descript
              from ARTICLE A inner join ARTICLE_LANGS B on B.ARTICLE_ID=A.ARTICLE_ID
              where A.STATUS=1
               {$where}
               and B.CMF_LANG_ID= {$lang}
              order by A.DATA desc
              {$limit}";
        } else {
            $sql = "select A.ARTICLE_ID
                   , A.ARTICLE_GROUP_ID
                   , A.URL
                   , A.IMAGE1
                   , A.NAME
                   , date_format(A.DATA,'%d.%m.%Y') as date
                   , A.DESCRIPTION as descript
              from ARTICLE A
              where A.STATUS=1
                {$where}
              order by A.DATA desc
              {$limit}";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getArticleGroupSingle($id, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.ARTICLE_GROUP_ID
                 , B.NAME
            from ARTICLE_GROUP A inner join ARTICLE_GROUP_LANGS B on B.ARTICLE_GROUP_ID=A.ARTICLE_GROUP_ID
            where A.ARTICLE_GROUP_ID=?
              and B.CMF_LANG_ID= {$lang}";
        } else {
            $sql = "select ARTICLE_GROUP_ID
                 , NAME
            from ARTICLE_GROUP
            where ARTICLE_GROUP_ID=?";
        }

        return $this->_db->fetchRow($sql, array($id));
    }

    public function getArticleSingle($id, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.ARTICLE_ID
                 , B.NAME
                 , DATE_FORMAT(A.DATA,'%d.%m.%y') as date
                 , A.ARTICLE_GROUP_ID
            from ARTICLE A inner join ARTICLE_LANGS B on B.ARTICLE_ID=A.ARTICLE_ID
            where A.ARTICLE_ID=?
              and B.CMF_LANG_ID= {$lang}";
        } else {
            $sql = "select ARTICLE_ID
                 , NAME
                 , DATE_FORMAT(DATA,'%d.%m.%y') as date
                 , ARTICLE_GROUP_ID
            from ARTICLE
            where ARTICLE_ID=?";
        }

        return $this->_db->fetchRow($sql, array($id));
    }

    public function getCatalogArticle($id, $lang, $limit = 0)
    {
        $sql_limit = '';
        if (!empty($limit))
            $sql_limit = " limit {$limit}";

        if ($lang > 0) {
            $sql = "select A.ARTICLE_ID
                 , B.NAME
                 , date_format(A.DATA,'%d.%m.%y') as date
                 , A.ARTICLE_GROUP_ID
            from ARTICLE A
               , CATALOGUE_ARTICLE_GROUP CAG
               , ARTICLE_LANGS B
            where CAG.CATALOGUE_ID = {$id}
              and CAG.ARTICLE_GROUP_ID = A.ARTICLE_GROUP_ID
              and B.ARTICLE_ID=A.ARTICLE_ID
              and A.STATUS = 1
              and B.CMF_LANG_ID= {$lang}
            {$sql_limit}";
        } else {
            $sql = "select A.ARTICLE_ID
                 , A.NAME
                 , date_format(A.DATA,'%d.%m.%y') as date
                 , A.ARTICLE_GROUP_ID
            from ARTICLE A
               , CATALOGUE_ARTICLE_GROUP CAG
            where CAG.CATALOGUE_ID = {$id}
              and CAG.ARTICLE_GROUP_ID = A.ARTICLE_GROUP_ID
              and A.STATUS = 1
            {$sql_limit}";
        }

        return $this->_db->fetchAll($sql);
    }

    public function searchArticle($query, $lang = 0)
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
            $sql = "select A.ARTICLE_ID
                  ,B.NAME
            from ARTICLE A inner join ARTICLE_LANGS B on B.ARTICLE_ID=A.ARTICLE_ID
            where B.CMF_LANG_ID= {$lang}
            {$where1}

            union

            select A.ARTICLE_ID
                  ,B.NAME
            from ARTICLE A inner join ARTICLE_LANGS B on B.ARTICLE_ID=A.ARTICLE_ID
            where B.CMF_LANG_ID= {$lang}
            {$where2}";
        } else {
            $sql = "select B.ARTICLE_ID
                  ,B.NAME
            from ARTICLE B
            where 1
            {$where1}

            union

            select B.ARTICLE_ID
                  ,B.NAME
            from ARTICLE B
            where 1
            {$where2}";
        }

        return $this->_db->fetchAll($sql);
    }

}

?>
