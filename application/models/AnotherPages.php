<?php
/**
 * Another Pages model
 *
 * @package Models
 * @author  Dima <john.doe@example.com>
 */
class AnotherPages extends Core_Connect
{

    public function getDefaultLang()
    {
        $sql = "select SYSTEM_NAME from CMF_LANG where STATUS=1 and IS_DEFAULT=1";
        return $this->_db->fetchOne($sql);
    }

    public function getLangs($where = '')
    {
        $sql = "select CMF_LANG_ID,NAME,SYSTEM_NAME,IS_DEFAULT,ICON from CMF_LANG where STATUS=1 order by ORDERING";
        return $this->_db->fetchAll($sql, $where);
    }

    public function getLanguageId($lang = '')
    {
        $lang_name = str_replace("/", "", $lang);

        if ($lang_name != 'ru') {
            $sql = "select CMF_LANG_ID
                 from CMF_LANG
                 where SYSTEM_NAME = ?";

            $lang_id = $this->_db->fetchOne($sql, $lang_name);
        }
        else
            $lang_id = 0;

        return $lang_id;
    }

    public function getTree($parentId = 0, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.ANOTHER_PAGES_ID
                        , A.PARENT_ID
                        , B.NAME
                        , A.CATNAME
                        , A.REALCATNAME
                        , A.URL
                        , A.IS_NEW_WIN
                        , A.IS_NODE
                        , A.VIA_JS
                        , A.MENU_WIDTH
                    from ANOTHER_PAGES A
                    inner join ANOTHER_PAGES_LANGS B on B.ANOTHER_PAGES_ID=A.ANOTHER_PAGES_ID
                    where A.PARENT_ID=?
                      and A.STATUS=1
                      and B.CMF_LANG_ID=?
                    order by ORDER_ asc";

            $menu = $this->_db->fetchAll($sql, array($parentId, $lang));
        } else {
            $sql = 'select ANOTHER_PAGES_ID
                        , PARENT_ID
                        , NAME
                        , CATNAME
                        , REALCATNAME
                        , URL
                        , IS_NEW_WIN
                        , IS_NODE
                        , VIA_JS
                        , MENU_WIDTH
                   from ANOTHER_PAGES
                   where PARENT_ID=?
                     and STATUS=1
                   order by ORDER_ asc';

            $menu = $this->_db->fetchAll($sql, $parentId);
        }

        return $menu;
    }
    
    public function getSiteMapTree()
    {
        $sql = 'select ANOTHER_PAGES_ID
                    , PARENT_ID
                    , NAME
                    , CATNAME
                    , REALCATNAME
                    , URL
                    , IS_NEW_WIN
                    , IS_NODE
               from ANOTHER_PAGES
               where STATUS=1
                 and PARENT_ID  > 0
               order by ORDER_ asc';

            return $this->_db->fetchAll($sql);
    }

    public function getTreeRecurs($parentId = 0)
    {
        $sql = 'select  *
               from ANOTHER_PAGES
               where PARENT_ID=?
                 and STATUS=1
               order by ORDER_ asc';

        $menu = $this->_db->fetchAll($sql, $parentId);

        for ($i = 0; $i < count($menu); $i++) {
            $children = $this->getTree($menu[$i]['ANOTHER_PAGES_ID']);

            if ($children)
                $menu[$i]['menu_children'] = $children;
        }

        return $menu;
    }

    public function getStatus($id)
    {
        $sql = "select STATUS
               from ANOTHER_PAGES
               where STATUS=1
                 and ANOTHER_PAGES_ID={$id}";

        $res = $this->_db->fetchOne($sql);

        return empty($res) ? false : true;
    }

    public function getDocId($id)
    {
        return $this->_db->fetchOne("select ANOTHER_PAGES_ID from ANOTHER_PAGES where REALCATNAME = '/" . $id . "/'");
    }

    public function getPageId($id)
    {
        return $this->_db->fetchOne("select ANOTHER_PAGES_ID from ANOTHER_PAGES where URL LIKE '%" . $id . "%'");
    }

    public function getPageByURL($id)
    {
        return $this->_db->fetchOne("select ANOTHER_PAGES_ID from ANOTHER_PAGES where URL = '" . $id . "'");
    }

    public function getDocRealCat($id)
    {
        return $this->_db->fetchOne("select REALCATNAME from ANOTHER_PAGES where ANOTHER_PAGES_ID = ?",
                $id);
    }

    public function getDocParentId($id)
    {
        return $this->_db->fetchOne("select PARENT_ID from ANOTHER_PAGES where ANOTHER_PAGES_ID = ?",
                $id);
    }

    public function getDocInfo($id, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.ANOTHER_PAGES_ID
                        , A.PARENT_ID
                        , B.NAME
                        , A.CATNAME
                        , A.REALCATNAME
                        , A.URL
                        , B.TITLE
                        , B.DESCRIPTION
                        , B.KEYWORDS
                        , A.IS_GROUP_NODE
                        , A.IMAGE1
                        , A.IS_NODE
                   from ANOTHER_PAGES A
                   inner join ANOTHER_PAGES_LANGS B on B.ANOTHER_PAGES_ID=A.ANOTHER_PAGES_ID
                   where A.ANOTHER_PAGES_ID=?
                     and B.CMF_LANG_ID=?";

            $info = $this->_db->fetchRow($sql, array($id, $lang));
        } else {
            $sql = "select ANOTHER_PAGES_ID
                        , PARENT_ID
                        , NAME
                        , CATNAME
                        , REALCATNAME
                        , URL
                        , TITLE
                        , DESCRIPTION
                        , KEYWORDS
                        , IS_GROUP_NODE
                        , IS_NODE
                        , IMAGE1
                   from ANOTHER_PAGES
                   where ANOTHER_PAGES_ID={$id}";

            $info = $this->_db->fetchRow($sql);
        }


        //XML
        if ($lang > 0)
            $xml = $this->_db->fetchOne("select XML from XMLS where TYPE=0 and XMLS_ID=? and CMF_LANG_ID=?",
                array($id, $lang));
        else
            $xml = $this->_db->fetchOne("select XML from XMLS where TYPE=0 and XMLS_ID=?",
                array($id));

        if ($xml) {
            $xml = str_replace("../images", "/images", $xml);
            $info['text'] = $xml;
        }

        return $info;
    }

    public function getPageInfo($id, $lang, $is_id)
    {
        if ($is_id) {
            if ($lang > 0) {
                $sql = "select A.ANOTHER_PAGES_ID
                         , A.PARENT_ID
                         , B.NAME
                         , A.REALCATNAME
                         , A.URL
                         , B.TITLE
                         , B.DESCRIPTION
                         , B.KEYWORDS
                    from ANOTHER_PAGES A
                    inner join ANOTHER_PAGES_LANGS B on B.ANOTHER_PAGES_ID=A.ANOTHER_PAGES_ID
                    where A.ANOTHER_PAGES_ID=" . $id . "
                      and B.CMF_LANG_ID=?";

                $info = $this->_db->fetchRow($sql, array($lang));
            } else {
                $sql = "select ANOTHER_PAGES_ID, PARENT_ID, NAME,REALCATNAME,URL,TITLE,DESCRIPTION,KEYWORDS from ANOTHER_PAGES where ANOTHER_PAGES_ID=" . $id;
                $info = $this->_db->fetchRow($sql);
            }
        } else {
            if ($lang > 0) {
                $sql = "select A.ANOTHER_PAGES_ID, A.PARENT_ID, B.NAME,A.REALCATNAME,A.URL,B.TITLE,B.DESCRIPTION,B.KEYWORDS from ANOTHER_PAGES A inner join ANOTHER_PAGES_LANGS B on B.ANOTHER_PAGES_ID=A.ANOTHER_PAGES_ID where A.URL LIKE '%" . $id . "%' and B.CMF_LANG_ID=?";
                $info = $this->_db->fetchRow($sql, array($lang));
            } else {
                $sql = "select ANOTHER_PAGES_ID, PARENT_ID, NAME,REALCATNAME,URL,TITLE,DESCRIPTION,KEYWORDS from ANOTHER_PAGES where URL LIKE '%" . $id . "%'";
                $info = $this->_db->fetchRow($sql);
            }
        }

        //XML
        if ($info) {
            if ($lang > 0)
                $xml = $this->_db->fetchOne("select XML from XMLS where TYPE=0 and XMLS_ID=? and CMF_LANG_ID=?",
                    array($id, $lang));
            else
                $xml = $this->_db->fetchOne("select XML from XMLS where TYPE=0 and XMLS_ID=?",
                    array($id));

            if ($xml) {
                $xml = str_replace("../images", "/images", $xml);
                $info['text'] = $xml;
            }
            else
                $info['text'] = '';
        }
        return $info;
    }

    public function getPath($id)
    {
        $path = array();
        $parents = $this->getParents($id);
        $parents = array_reverse($parents);
        if ($parents) {
            for ($i = 0; $i < sizeof($parents); $i++) {
                $docinfo = $this->getDocInfo($parents[$i]);
                $path[] = $docinfo;
            }
        }
        $docinfo2 = $this->getDocInfo($id);
        $path[] = $docinfo2;
        return $path;
    }

    public function getParents($id)
    {
        $path = array();
        $sql = "select PARENT_ID from ANOTHER_PAGES where ANOTHER_PAGES_ID=? and STATUS=1 order by NAME";
        $parent = $this->_db->fetchOne($sql, $id);

        if ($parent > 0) {
            $path[] = $parent;
            $path = array_merge($path, $this->getParents($parent));
        }

        return $path;
    }

    public function getSectionImage($id)
    {
        $sql = "select PARENT_ID
                    , IMAGE1
               from ANOTHER_PAGES
               where ANOTHER_PAGES_ID=?";

        $another_pages = $this->_db->fetchRow($sql, $id);

        $image = $another_pages['IMAGE1'];
        if (empty($image) && $another_pages['PARENT_ID'] > 0) {
            $image = $this->getSectionImage($another_pages['PARENT_ID']);
        }

        return $image;
    }

    public function getChildren($id)
    {
        $path = array();
        $sql = "select ANOTHER_PAGES_ID from ANOTHER_PAGES where PARENT_ID=? and STATUS=1 order by NAME";
        $childs = $this->_db->fetchAll($sql, $id);
        return $childs;
    }

    public function getSubmenu($id, $lang)
    {
        $children = $this->getChildren($id);
        $submenu = array();
        if ($children) {
            for ($i = 0; $i < sizeof($children); $i++) {
                $info = $this->getDocInfo(
                    $children[$i]['ANOTHER_PAGES_ID'], $lang
                );
                if ($info)
                    $submenu[] = $info;
            }
        }
        return $submenu;
    }

    public function getDocXml($xmlsId, $type, $lang)
    {
        $sql = "select XML
            from XMLS
            where XMLS_ID={$xmlsId}
              and TYPE={$type}
              and CMF_LANG_ID = {$lang}";

        return $this->_db->fetchOne($sql);
    }

    public function getHeader($lang = 0)
    {
        if ($lang > 0) {
            $sql = "select B.DESCRIPTION
                      , B.IMAGE
                      , B.IMAGE1
                      , B.URL
                  from HEADER A
                  inner join HEADER_CMF_LANG B on B.HEADER_ID=A.HEADER_ID
                  where A.STATUS=1
                    and B.CMF_LANG_ID={$lang}
                  order by A.ORDERING";
        } else {
            $sql = 'select *
                 from HEADER
                 where STATUS=1
                 order by ORDERING';
        }


        return $this->_db->fetchAll($sql);
    }

    public function getLeftBannersGroups()
    {
        $sql = 'select LBG.*
                from LEFT_BANNS_GROUP LBG
                inner join LEFT_BANNS LB on (LB.LEFT_BANNS_GROUP_ID = LBG.LEFT_BANNS_GROUP_ID) and LB.STATUS=1
                where LBG.STATUS=1
                group by LBG.LEFT_BANNS_GROUP_ID
                order by LBG.ORDERING';

        return $this->_db->fetchAll($sql);
    }

    public function getLeftBanners($leftBannGroupId, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select B.DESCRIPTION
                      , B.IMAGE
                      , B.IMAGE1
                      , B.URL
                  from LEFT_BANNS A
                  inner join LEFT_BANNS_CMF_LANG B on B.HEADER_ID=A.HEADER_ID
                  where A.STATUS=1
                    and B.CMF_LANG_ID={$lang}
                    and A.LEFT_BANNS_GROUP_ID = ?
                  order by A.ORDERING";
        } else {
            $sql = 'select *
                    from LEFT_BANNS
                    where STATUS=1
                      and LEFT_BANNS_GROUP_ID = ?
                    order by ORDERING';
        }


        return $this->_db->fetchAll($sql, array($leftBannGroupId));
    }

    public function getCallbackTime($lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.CALLBACK_ID
                      , B.NAME
                  from CALLBACK_TIME A
                  inner join CALLBACK_TIME_LANG B on B.CALLBACK_TIME_ID=A.CALLBACK_TIME_ID
                  where B.CMF_LANG_ID={$lang}
                  order by A.ORDERING";
        } else {
            $sql = 'select *
                from CALLBACK_TIME
                order by ORDERING';
        }

        return $this->_db->fetchAll($sql);
    }

    public function getCallbackTimeName($callBackTimeId, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select B.NAME
                  from CALLBACK_TIME A
                  inner join CALLBACK_TIME_LANG B on B.CALLBACK_TIME_ID=A.CALLBACK_TIME_ID
                  where A.CALLBACK_TIME_ID = {$callBackTimeId}
                    and B.CMF_LANG_ID={$lang}";
        } else {
            $sql = "select NAME
                from CALLBACK_TIME
                where CALLBACK_TIME_ID = {$callBackTimeId}";
        }

        return $this->_db->fetchOne($sql);
    }

    public function insertData($table, $data)
    {
        $this->_db->insert($table, $data);

        return $this->_db->lastInsertId();
    }

    public function getSefURLbyOldURL($oldURL)
    {
        $oldURL = str_replace("&amp;", "&", $oldURL);
        $oldURL = str_replace("&", "&amp;", $oldURL);

        $sql = "select S.SEF_URL
                from OLD_SEF_URL O join SEF_SITE_URL S using (SEF_SITE_URL_ID)
                where O.NAME = '$oldURL'";


        return str_replace('&amp;', '&', $this->_db->fetchOne($sql));
    }

    public function getSiteURLbySEFU($sefURL)
    {
        $sefURL = preg_replace("/\/$/", "", $sefURL);
        $sefURL = str_replace('&amp;', '&', $sefURL);
        $sefURL = str_replace('&', '&amp;', $sefURL);
        $sefURLDecode = urldecode($sefURL);
        $sefURL = mysql_escape_string($sefURL);
        $sefURLDecode = mysql_escape_string($sefURLDecode);
        $sql = "select SITE_URL
                from SEF_SITE_URL
                where SEF_URL rlike '^{$sefURL}.?$'
                   or SEF_URL rlike '^$sefURLDecode.?$'
                order by SEF_SITE_URL_ID desc";

        return $this->_db->fetchOne($sql);
    }

    /**
     * Получить все настройки по подмене телефона
     *
     * @return array
     */
    public function getRefererPhones()
    {
        $sql = "select *
                from REFERER_PHONES";

        return $this->_db->fetchAll($sql);
    }

    /**
     * Получить код уровня сокобана
     *
     * @param int $level уровень
     *
     * @return string
     */
    public function getSokobamLevelCode($level)
    {
        $sql = "select LEVEL_CODE_JSON
                from SOKOBAN
                where LEVEL = ?";

        return $this->_db->fetchOne($sql, $level);
    }

    /**
     * Получить максимальный уровень
     *
     * @return int
     */
    public function getSokobamMaxLevel()
    {
        $sql = "select max(LEVEL)
                from SOKOBAN
                where STATUS = 1";

        return $this->_db->fetchOne($sql);
    }

    /**
     * Получить все уровни
     *
     * @return string
     */
    public function getSokobamLevels()
    {
        $sql = "select LEVEL
                from SOKOBAN
                where STATUS = 1
                order by LEVEL asc";

        return $this->_db->fetchCol($sql);
    }

}