<?php
class Gallery extends Core_Connect
{
    protected $_name = 'GALLERY';

    public function getGalleryGroup($pid, $lang)
    {
        if ($lang > 0) {
            $sql = "select  A.GALLERY_GROUP_ID
                      , A.PARENT_ID
                      , A.IMAGE1
                      , A.STYLE
                      , B.NAME
                      , B.DESCRIPTION
                from GALLERY_GROUP A inner join GALLERY_GROUP_LANGS B on B.GALLERY_GROUP_ID=A.GALLERY_GROUP_ID
                where A.STATUS=1
                  and A.PARENT_ID= {$pid}
                  and B.CMF_LANG_ID= {$lang}
                order by A.ORDERING";
        } else {
            $sql = "select  GALLERY_GROUP_ID
                      , PARENT_ID
                      , IMAGE1
                      , STYLE
                      , NAME
                      , DESCRIPTION
                from GALLERY_GROUP
                where STATUS=1
                  and PARENT_ID= {$pid}
                order by ORDERING";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getSubGroupGallery($id)
    {
        $sql = "select count(*)
                from GALLERY_GROUP
                where PARENT_ID = {$id}
                  and STATUS=1";

        return $this->_db->fetchOne($sql);
    }

    public function getParents($id)
    {
        $sql = "select PARENT_ID
                 , GALLERY_GROUP_ID
                 , NAME
            from GALLERY_GROUP
            where GALLERY_GROUP_ID = ?
              and STATUS=1";

        return $this->_db->fetchRow($sql, $id);
    }

    public function getVideoParents($id)
    {
        $sql = "select PARENT_ID
                 , GALLERY_GROUP_VIDEO_ID
                 , NAME
            from GALLERY_GROUP_VIDEO
            where GALLERY_GROUP_VIDEO_ID = ?
              and STATUS=1";

        return $this->_db->fetchRow($sql, $id);
    }

    public function getGallery($id, $lang)
    {
        if ($lang > 0) {
            $sql = "select  A.GALLERY_ID
                      , A.IMAGE1
                      , A.IMAGE2
                      , B.NAME
                      , B.DESCRIPTION
                from GALLERY A inner join GALLERY_LANGS B on B.GALLERY_ID=A.GALLERY_ID
                where A.STATUS=1
                  and B.CMF_LANG_ID= {$lang}
                  and A.GALLERY_GROUP_ID= {$id}
                order by A.ORDERING";
        } else {
            $sql = "select  GALLERY_ID
                      , IMAGE1
                      , IMAGE2
                      , NAME
                      , DESCRIPTION
                from GALLERY
                where STATUS=1
                  and GALLERY_GROUP_ID = {$id}
                order by ORDERING";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getGroupGalleryID($id)
    {
        $sql = "select GALLERY_GROUP_ID
          from GALLERY
          where GALLERY_ID = {$id}";

        return $this->_db->fetchOne($sql);
    }

    public function getGroupVideoGalleryID($id)
    {
        $sql = "select GALLERY_GROUP_VIDEO_ID
          from GALLERY_VIDEO
          where GALLERY_VIDEO_ID = {$id}";

        return $this->_db->fetchOne($sql);
    }

    public function getGalleryInfo($id, $lang)
    {
        if ($lang > 0) {
            $sql = "select  B.NAME
                      , B.DESCRIPTION
                from GALLERY A inner join GALLERY_LANGS B on B.GALLERY_ID=A.GALLERY_ID
                where A.GALLERY_ID={$id}
                  and B.CMF_LANG_ID= {$lang}";
        } else {
            $sql = "select  NAME
                          , DESCRIPTION
                    from GALLERY
                    where GALLERY_ID={$id}";
        }

        return $this->_db->fetchRow($sql);
    }

    public function getGroupGalleryInfo($id, $lang)
    {
        if ($lang > 0) {
            $sql = "select  B.NAME
                      , B.DESCRIPTION
                from GALLERY_GROUP A inner join GALLERY_GROUP_LANGS B on B.GALLERY_GROUP_ID=A.GALLERY_GROUP_ID
                where A.GALLERY_GROUP_ID={$id}
                  and B.CMF_LANG_ID= {$lang}";
        } else {
            $sql = "select  NAME
                      , DESCRIPTION
                from GALLERY_GROUP
                where GALLERY_GROUP_ID={$id}";
        }

        return $this->_db->fetchRow($sql);
    }

    public function getGroupVideoGalleryInfo($id, $lang)
    {
        if ($lang > 0) {
            $sql = "select  B.NAME
                      , B.DESCRIPTION
                from GALLERY_GROUP A inner join GALLERY_GROUP_VIDEO_LANGS B on B.GALLERY_GROUP_VIDEO_ID=A.GALLERY_GROUP_VIDEO_ID
                where A.GALLERY_GROUP_VIDEO_ID={$id}
                  and B.CMF_LANG_ID= {$lang}";
        } else {
            $sql = "select  NAME
                      , DESCRIPTION
                from GALLERY_GROUP_VIDEO
                where GALLERY_GROUP_VIDEO_ID={$id}";
        }

        return $this->_db->fetchRow($sql);
    }

    public function getGalleryVideoGroup($pid, $lang)
    {
        if ($lang > 0) {
            $sql = "select  A.GALLERY_GROUP_ID
                      , A.PARENT_ID
                      , A.IMAGE1
                      , A.STYLE
                      , B.NAME
                      , B.DESCRIPTION
                from GALLERY_GROUP A inner join GALLERY_GROUP_VIDEO_LANGS B on B.GALLERY_GROUP_VIDEO_ID=A.GALLERY_GROUP_VIDEO_ID
                where A.STATUS=1
                  and A.PARENT_ID= {$pid}
                  and B.CMF_LANG_ID= {$lang}
                order by A.ORDERING";
        } else {
            $sql = "select  GALLERY_GROUP_VIDEO_ID
                      , PARENT_ID
                      , IMAGE1
                      , STYLE
                      , NAME
                      , DESCRIPTION
                from GALLERY_GROUP_VIDEO
                where STATUS=1
                  and PARENT_ID= {$pid}
                order by ORDERING";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getVideoGallery($id, $lang)
    {
        if ($lang > 0) {
            $sql = "select  A.GALLERY_VIDEO_ID
                      , A.IMAGE1
                      , A.IMAGE2
                      , A.CODE_VIDEO
                      , B.NAME
                      , B.DESCRIPTION
                from GALLERY_VIDEO A inner join GALLERY_VIDEO_LANGS B on B.GALLERY_VIDEO_ID=A.GALLERY_VIDEO_ID
                where A.STATUS=1
                  and B.CMF_LANG_ID= {$lang}
                  and A.GALLERY_GROUP_VIDEO_ID= {$id}
                order by A.ORDERING";
        } else {
            $sql = "select  GALLERY_VIDEO_ID
                      , IMAGE1
                      , IMAGE2
                      , CODE_VIDEO
                      , NAME
                      , DESCRIPTION
                from GALLERY_VIDEO
                where STATUS=1
                  and GALLERY_GROUP_VIDEO_ID = {$id}
                order by ORDERING";
        }

        return $this->_db->fetchAll($sql);
    }

    public function getSubVideGroupGallery($id)
    {
        $sql = "select count(*)
          from GALLERY_GROUP_VIDEO
          where PARENT_ID = {$id}
            and STATUS=1";

        return $this->_db->fetchOne($sql);
    }

    public function getVideoCode($id)
    {
        $sql = "select CODE_VIDEO
          from GALLERY_VIDEO
          where GALLERY_VIDEO_ID = {$id}";

        return $this->_db->fetchOne($sql, $id);
    }

    public function getVideoFile($id)
    {
        $sql = "select IMAGE2
          from GALLERY_VIDEO
          where GALLERY_VIDEO_ID = {$id}";

        return $this->_db->fetchOne($sql, $id);
    }

    public function getAllParents($id, $path = array())
    {
        $sql = "select PARENT_ID
            from GALLERY_GROUP
            where GALLERY_GROUP_ID= ?
              and STATUS=1";

        $parent_id = $this->_db->fetchOne($sql, $id);

        if (!empty($parent_id)) {
            $path[count($path)] = $parent_id;
            $path = $this->getAllParents($parent_id, $path);
        }

        return $path;
    }

    public function getAllVideoParents($id, $path = array())
    {
        $sql = "select PARENT_ID
            from GALLERY_GROUP_VIDEO
            where GALLERY_GROUP_VIDEO_ID = ?
              and STATUS=1";

        $parent_id = $this->_db->fetchOne($sql, $id);

        if (!empty($parent_id)) {
            $path[count($path)] = $parent_id;
            $path = $this->getAllParents($parent_id, $path);
        }

        return $path;
    }

}

?>