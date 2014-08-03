<?php
class Catalogue extends Core_Connect
{
    public function getTree($parentId = 0, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.CATALOGUE_ID
                    ,A.PARENT_ID
                    ,B.NAME
                    ,A.CATNAME
                    ,A.REALCATNAME
                    ,A.COLOR_STYLE
                    ,A.IMAGE1
                    ,A.IMAGE2
                    ,A.STATUS
                    ,A.STATUS_MAIN
                    ,A.IN_MENU
                    ,A.ITEM_IS_DESCR
                    ,A.URL
              from CATALOGUE A inner join CATALOGUE_LANGS B on B.CATALOGUE_ID=A.CATALOGUE_ID
              where A.PARENT_ID=?
                and A.REALSTATUS=1
              order by A.ORDERING";
        } else {
            $sql = "select CATALOGUE_ID
                    ,PARENT_ID
                    ,NAME
                    ,CATNAME
                    ,REALCATNAME
                    ,COLOR_STYLE
                    ,IMAGE1
                    ,IMAGE2
                    ,STATUS
                    ,STATUS_MAIN
                    ,IN_MENU
                    ,ITEM_IS_DESCR
                    ,URL
              from CATALOGUE
              where PARENT_ID=?
                and REALSTATUS=1
              order by ORDERING";
        }

        return $this->_db->fetchAll($sql, $parentId);
    }

    public function getItems($id, $lang)
    {
        if ($lang > 0) {
            $sql = "select A.CATALOGUE_ID
                    ,A.ITEM_ID
                    ,B.NAME
                    ,B.MENU_NAME
                    ,B.DESCRIPTION
                    ,A.IMAGE
                    ,A.STATUS
                    ,A.STATUS_MAIN
              from ITEM A inner join ITEM_LANGS B on B.ITEM_ID=A.ITEM_ID
              where A.CATALOGUE_ID=?
                and A.STATUS=1
              order by A.ORDERING";
        } else {
            $sql = "select CATALOGUE_ID
                    ,ITEM_ID
                    ,NAME
                    ,MENU_NAME
                    ,DESCRIPTION
                    ,IMAGE
                    ,STATUS
                    ,STATUS_MAIN
              from ITEM
              where CATALOGUE_ID=?
                and STATUS=1
              order by ORDERING";
        }

        return $this->_db->fetchAll($sql, $id);
    }

    public function getChildrenCount($id)
    {
        $sql = "select count(*)
            from CATALOGUE
            where PARENT_ID=?
              and STATUS=1";

        return $this->_db->fetchOne($sql, $id);
    }

    public function getItemsCount($id)
    {
        $sql = "select count(*)
            from ITEM
            where CATALOGUE_ID={$id}
              and STATUS=1";

        return $this->_db->fetchOne($sql);
    }

    public function getCatFirstItems($id)
    {
        $sql = "select ITEM_ID
            from ITEM
            where CATALOGUE_ID={$id}
              and STATUS=1
            limit 1";

        return $this->_db->fetchOne($sql);
    }

    public function getChildren($id)
    {
        $sql = "select CATALOGUE_ID
            from CATALOGUE
            where PARENT_ID=?
              and STATUS=1
            order by ORDERING,CATALOGUE_ID";

        return $this->_db->fetchCol($sql, $id);
    }

    public function getAllChildren($id)
    {
        $path = array();
        $sql = "select CATALOGUE_ID
            from CATALOGUE
            where PARENT_ID=?
              and STATUS=1
            order by ORDERING,CATALOGUE_ID";

        $childs = $this->_db->fetchAll($sql, $id);

        if (count($childs) > 0) {
            foreach ($childs as $child) {
                if ($child['CATALOGUE_ID'] > 0) {
                    $path[] = $child['CATALOGUE_ID'];
                    $path = array_merge($path,
                                        $this->getChildren($child['CATALOGUE_ID']));
                }
            }
        }

        return $path;
    }

    /**
     * Метод для получения ИД каталога для товара
     * @access   public
     * @param    integer $id
     * @return   integer $catalog
     */
    public function getItemCatalog($id)
    {
        $sql = "select CATALOGUE_ID from ITEM where ITEM_ID=?";
        return $this->_db->fetchOne($sql, $id);
    }

    public function getAllParents($id, $path)
    {
        if (empty($id)) {
            return array();
        }

        $sql = "select PARENT_ID
                from CATALOGUE
                where CATALOGUE_ID = ?
                  and STATUS=1";

        $parent_id = $this->_db->fetchOne($sql, $id);

        if (!empty($parent_id)) {
            $path[count($path)] = $parent_id;
            $path = $this->getAllParents($parent_id, $path);
        }

        return $path;
    }

    public function getCatalogueId($cat)
    {
        $sql = "select CATALOGUE_ID
          from CATALOGUE
          where REALCATNAME = '/" . $cat . "/'";

        return $this->_db->fetchOne($sql);
    }

    public function getCatalogueIdByUrl($cat)
    {
        $sql = "select CATALOGUE_ID
          from CATALOGUE
          where URL  = '{$cat}'";

        return $this->_db->fetchOne($sql);
    }

    public function getCatName($cid, $lang)
    {
        return $this->_db->fetchOne("select NAME from CATALOGUE where CATALOGUE_ID=?",
                                    array($cid));
    }

    public function getParentId($cid)
    {
        $sql = "select PARENT_ID from CATALOGUE where CATALOGUE_ID=?";
        return $this->_db->fetchOne($sql, $cid);
    }

    public function getParents($id, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.CATALOGUE_ID
                    ,A.PARENT_ID
                    ,B.NAME
                    ,A.REALCATNAME
                    ,A.URL
              from CATALOGUE A inner join CATALOGUE_LANGS B on B.CATALOGUE_ID=A.CATALOGUE_ID
              where A.CATALOGUE_ID=?
                and A.STATUS=1";
        } else {
            $sql = "select PARENT_ID
                   , CATALOGUE_ID
                   , NAME
                   , URL
                   , REALCATNAME
              from CATALOGUE
              where CATALOGUE_ID=?
                and STATUS=1";
        }

        return $this->_db->fetchRow($sql, $id);
    }

    public function getCatInfo($id, $lang)
    {
        if ($lang > 0) {
            $sql = "select A.CATALOGUE_ID
                    ,A.PARENT_ID
                    ,B.NAME
                    ,B.TITLE
                    ,A.CATNAME
                    ,A.REALCATNAME
                    ,A.COLOR_STYLE
                    ,A.IMAGE1
                    ,A.IMAGE2
                    ,A.DESCRIPTION
                    ,A.STATUS
                    ,A.STATUS_MAIN
                    ,A.IN_MENU
                    ,A.ITEM_IS_DESCR
                    ,A.URL
                    ,B.HTML_DESCRIPTION
                    ,B.HTML_KEYWORDS
              from CATALOGUE A inner join CATALOGUE_LANGS B on B.CATALOGUE_ID=A.CATALOGUE_ID
              where A.CATALOGUE_ID=?";
        } else {
            $sql = "select CATALOGUE_ID
                    ,PARENT_ID
                    ,NAME
                    ,TITLE
                    ,CATNAME
                    ,REALCATNAME
                    ,COLOR_STYLE
                    ,IMAGE1
                    ,IMAGE2
                    ,DESCRIPTION
                    ,STATUS
                    ,STATUS_MAIN
                    ,IN_MENU
                    ,ITEM_IS_DESCR
                    ,URL
                    ,HTML_DESCRIPTION
                    ,HTML_KEYWORDS
              from CATALOGUE
              where CATALOGUE_ID=?";
        }

        return $this->_db->fetchRow($sql, array($id));
    }

    public function getItemInfo($id, $lang)
    {
        if ($lang > 0) {
            $sql = "select A.CATALOGUE_ID
                    ,B.NAME
                    ,B.POP_IMAGE_TEXT
                    ,B.UNDER_IMAGE_TEXT
                    ,A.IMAGE1
                    ,A.IMAGE2
                    ,A.CODE_MAP_AREA
                    ,A.IS_FORM
                    ,C.ITEM_IS_DESCR
              from ITEM A inner join ITEM_LANGS B on B.ITEM_ID=A.ITEM_ID
              join CATALOGUE C on (C.CATALOGUE_ID = A.CATALOGUE_ID)
              where A.ITEM_ID=?";
        } else {
            $sql = "select A.CATALOGUE_ID
                    ,A.NAME
                    ,A.POP_IMAGE_TEXT
                    ,A.UNDER_IMAGE_TEXT
                    ,A.IMAGE1
                    ,A.IMAGE2
                    ,A.CODE_MAP_AREA
                    ,A.IS_FORM
                    ,C.ITEM_IS_DESCR
              from ITEM A
              join CATALOGUE C on (C.CATALOGUE_ID = A.CATALOGUE_ID)
              where A.ITEM_ID=?";
        }

        return $this->_db->fetchRow($sql, array($id));
    }
    
    public function getItemMeta($id, $lang)
    {
        if ($lang > 0) {
            $sql = "select B.HTML_TITLE
                          , B.HTML_KEYWORDS
                          , B.HTML_DESCRIPTION
                  from ITEM A inner join ITEM_LANGS B on B.ITEM_ID=A.ITEM_ID
                  where A.ITEM_ID=?";
        } else {
            $sql = "select A.HTML_TITLE
                    ,A.HTML_KEYWORDS
                    ,A.HTML_DESCRIPTION
              from ITEM A
              where A.ITEM_ID=?";
        }

        return $this->_db->fetchRow($sql, array($id));
    }

    public function getItemName($id, $lang)
    {
        if ($lang > 0) {
            $sql = "select B.NAME
              from ITEM A inner join ITEM_LANGS B on B.ITEM_ID=A.ITEM_ID
              where A.ITEM_ID=?";
        } else {
            $sql = "select A.NAME
              from ITEM A
              where A.ITEM_ID=?";
        }

        return $this->_db->fetchOne($sql, array($id));
    }

    public function getItemElementsInfo($id, $lang)
    {
        if ($lang > 0) {
            $sql = "select A.NAME_NUM
                    ,B.NAME
                    ,B.DESCRIPTION
                    ,A.IMAGE1
                    ,A.ITEM_ELEMENTS_ID
              from ITEM_ELEMENTS A inner join ITEM_ELEMENTS_LANGS B on B.ITEM_ELEMENTS_ID=A.ITEM_ELEMENTS_ID
              where A.ITEM_ID=?
              order by A.NAME_NUM";
        } else {
            $sql = "select NAME_NUM
                    ,NAME
                    ,DESCRIPTION
                    ,IMAGE1
                    ,ITEM_ELEMENTS_ID
              from ITEM_ELEMENTS
              where ITEM_ID=?
              order by NAME_NUM";
        }

        return $this->_db->fetchAll($sql, array($id));
    }

    public function getBanners($align, $section)
    {
        $sql = "select SECTION_ALIGN_ID, IMAGE1,ALT,DESCRIPTION,TYPE,URL,NEWWIN from SECTION_ALIGN where ALIGN_ID=? and BANN_SECTION_ID=? and STATUS=1";
        $banners = $this->_db->fetchAll($sql, array($align, $section));

        $burl = '';
        for ($i = 0; $i < sizeof($banners); $i++) {
            if ($banners[$i]['URL'] != '' || strchr($banners[$i]['URL'], "http:"))
                $burl = $banners[$i]['URL'];
            else {
                if ($banners[$i]['URL'] != '') {
                    if (strchr($banners[$i]['URL'], "doc")) {
                        if (substr($banners[$i]['URL'], 0, 1) != "/")
                            $burl .= "/";
                        $burl .= $banners[$i]['URL'];
                    }
                    else {
                        if (substr($banners[$i]['URL'], 0, 1) != "/")
                            $burl = "/doc/" . $banners[$i]['URL'];
                        else
                            $burl = "/doc" . $banners[$i]['URL'];
                    }
                    if (substr($banners[$i]['URL'], -1) != "/")
                        $burl .="/";
                } else
                    $burl = '';
            }
            if ($burl != '')
                $banners[$i]['burl'] = $burl;
            else
                $banners[$i]['burl'] = '';
        }
        return $banners;
    }

    public function getFrontImage($id)
    {
        $sql = "select IMAGE1
                from CATALOGUE
                where TO_PARENT = 1
                and PARENT_ID = {$id}";

        return $this->_db->fetchOne($sql);
    }

    public function insertZakaz($data)
    {
        try {
            // Проверяем на NULL для обязательных полей
            if (null === $data['NAME'] || null === $data['TELMOB'])
                throw new Exception('Need a NAME and TELMOB');

            $this->_db->insert('ZAKAZ', $data);

            return $this->_db->lastInsertId();
        } catch (Exception $exc) {
            //echo $exc->getTraceAsString();
        }
    }

    public function insertOrder($data)
    {
        $sql = "insert into ZAKAZ_ITEM
          set ZAKAZ_ID = {$data['ZAKAZ_ID']}
            , CATALOGUE_ID = {$data['CATALOGUE_ID']}
            , NAME = '{$data['NAME']}'
            , ITEM_ID = {$data['ITEM_ID']}";

        $this->_db->query($sql);
    }

    public function searchCatalog($query, $lang = 0)
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
            $sql = "select A.CATALOGUE_ID
                  ,B.NAME
                  ,A.CATNAME
                  ,A.REALCATNAME
                  ,A.URL
                  ,A.ITEM_IS_DESCR
            from CATALOGUE A inner join CATALOGUE_LANGS B on B.CATALOGUE_ID=A.CATALOGUE_ID
            where B.CMF_LANG_ID= {$lang}
            {$where1}

            union

            select A.CATALOGUE_ID
                  ,B.NAME
                  ,A.CATNAME
                  ,A.REALCATNAME
                  ,A.URL
                  ,A.ITEM_IS_DESCR
            from CATALOGUE A inner join CATALOGUE_LANGS B on B.CATALOGUE_ID=A.CATALOGUE_ID
            where B.CMF_LANG_ID= {$lang}
            {$where2}";
        } else {
            $sql = "select B.CATALOGUE_ID
                  ,B.NAME
                  ,B.CATNAME
                  ,B.REALCATNAME
                  ,B.URL
                  ,B.ITEM_IS_DESCR
            from CATALOGUE B
            where 1
            {$where1}

            union

            select B.CATALOGUE_ID
                  ,B.NAME
                  ,B.CATNAME
                  ,B.REALCATNAME
                  ,B.URL
                  ,B.ITEM_IS_DESCR
            from CATALOGUE B
            where 1
            {$where2}";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getItemFotos($id, $lang)
    {
        if ($lang > 0) {
            $sql = "select B.NAME
                    ,B.DESCRIPTION
                    ,A.IMAGE1
                    ,A.IMAGE2
              from ITEM_PHOTO IP
                 , GALLERY A inner join GALLERY_LANGS B on B.GALLERY_ID=A.GALLERY_ID
              where IP.ITEM_ID={$id}
                and IP.GALLERY_ID = B.GALLERY_ID
                and B.CMF_LANG_ID = {$lang}
                and A.STATUS=1
              order by IP.ORDERING_";
        } else {
            $sql = "select A.NAME
                    ,A.DESCRIPTION
                    ,A.IMAGE1
                    ,A.IMAGE2
              from ITEM_PHOTO IP
                 , GALLERY A
              where IP.ITEM_ID={$id}
                and IP.GALLERY_ID = A.GALLERY_ID
                and A.STATUS=1
              order by IP.ORDERING_";
        }

        return $this->_db->fetchAll($sql);
    }

    public function searchItems($query, $lang = 0)
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
            $sql = "select A.ITEM_ID
                  ,A.CATALOGUE_ID
                  ,B.NAME
            from ITEM A inner join ITEM_LANGS B on B.ITEM_ID=A.ITEM_ID
            where B.CMF_LANG_ID= {$lang}
            {$where1}

            union

            select A.ITEM_ID
                  ,A.CATALOGUE_ID
                  ,B.NAME
            from ITEM A inner join ITEM_LANGS B on B.ITEM_ID=A.ITEM_ID
            where B.CMF_LANG_ID= {$lang}
            {$where2}";
        } else {
            $sql = "select B.ITEM_ID
                  ,B.CATALOGUE_ID
                  ,B.NAME
            from ITEM B
            where 1
            {$where1}

            union

            select B.ITEM_ID
                  ,B.CATALOGUE_ID
                  ,B.NAME
            from ITEM B
            where 1
            {$where2}";
        }

        return $this->_db->fetchAll($sql);
    }

    /**
     * Получить картинку раздела
     *
     * @param $id
     *
     * @return string
     */
    public function getSectionImage($id)
    {
        $image = '';

        while (!empty($image) || !empty($id)) {
            $sql = "select PARENT_ID
                         , IMAGE2
                    from CATALOGUE
                    where CATALOGUE_ID = ?";

            $result = $this->_db->fetchRow($sql, $id);
            $id = $result['PARENT_ID'];
            $image = $result['IMAGE2'];

            if (!empty($image)) {
                return $image;
            }
        }

        return $image;
    }

    /**
     * @param int $id
     *
     * @return int
     */
    public function getTemplate($id)
    {
        $sql = "select TEMPLATE
                from CATALOGUE
                where CATALOGUE_ID = ?";

        return (int) $this->_db->fetchOne($sql, $id);
    }

}