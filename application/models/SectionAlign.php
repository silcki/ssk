<?php
class SectionAlign extends Core_Connect
{
    public function GetBanns($where)
    {
        $sql = "select ALIGN_ID
                ,IMAGE1
                ,TYPE
                ,ALT
                ,DESCRIPTION
                ,URL
                ,NEWWIN
          from SECTION_ALIGN
          where BANN_SECTION_ID=?
            and STATUS=1";

        return $this->_db->fetchAll($sql, array($where));
    }

    public function getBanners($align, $section, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.SECTION_ALIGN_ID
                    ,B.IMAGE1
                    ,B.ALT
                    ,B.DESCRIPTION
                    ,A.TYPE
                    ,A.BANNER_CODE
                    ,A.URL
                    ,A.NEWWIN
              from SECTION_ALIGN A inner join SECTION_ALIGN_LANGS B on B.SECTION_ALIGN_ID=A.SECTION_ALIGN_ID
              where A.ALIGN_ID=?
                and A.BANN_SECTION_ID=?
                and B.CMF_LANG_ID={$lang}
                and A.STATUS=1";
        } else {
            $sql = "select SECTION_ALIGN_ID
                    ,IMAGE1
                    ,ALT
                    ,DESCRIPTION
                    ,BANNER_CODE
                    ,TYPE
                    ,URL
                    ,NEWWIN
              from SECTION_ALIGN
              where ALIGN_ID=?
                and BANN_SECTION_ID=?
                and STATUS=1";
        }

        return $this->_db->fetchAll($sql, array($align, $section));
    }

    public function getBanner($align, $section, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select A.SECTION_ALIGN_ID
                    ,B.IMAGE1
                    ,B.ALT
                    ,B.DESCRIPTION
                    ,A.TYPE
                    ,A.URL
                    ,A.NEWWIN
              from SECTION_ALIGN A inner join SECTION_ALIGN_LANGS B on B.SECTION_ALIGN_ID=A.SECTION_ALIGN_ID
              where A.ALIGN_ID=?
                and A.BANN_SECTION_ID=?
                and B.CMF_LANG_ID={$lang}
                and A.STATUS=1";
        } else {
            $sql = "select SECTION_ALIGN_ID
                    ,IMAGE1
                    ,ALT
                    ,DESCRIPTION
                    ,TYPE
                    ,URL
                    ,NEWWIN
              from SECTION_ALIGN
              where ALIGN_ID=?
                and BANN_SECTION_ID=?
                and STATUS=1";
        }

        return $this->_db->fetchRow($sql, array($align, $section));
    }

    public function getRandomBanner($align, $section, $lang = 0)
    {
        $sql = "select SECTION_ALIGN_ID,
                    IMAGE1,
                    ALT,
                    DESCRIPTION,
                    TYPE,
                    URL,
                    NEWWIN
             from SECTION_ALIGN
             where ALIGN_ID=?
               and BANN_SECTION_ID=?
               and STATUS=1
             order by RAND() limit 0,1";

        $banner = $this->_db->fetchRow($sql, array($align, $section));

        $burl = '';
        if ($banner['URL'] != '' || strchr($banner['URL'], "http:"))
            $burl = $banner['URL'];
        else {
            if ($banner['URL'] != '') {
                if (strchr($banner['URL'], "doc")) {
                    if (substr($banner['URL'], 0, 1) != "/")
                        $burl .= "/";
                    $burl .= $banner['URL'];
                }
                else {
                    if (substr($banner['URL'], 0, 1) != "/")
                        $burl = "/doc/" . $banner['URL'];
                    else
                        $burl = "/doc" . $banner['URL'];
                }
                if (substr($banner['URL'], -1) != "/")
                    $burl .="/";
            } else
                $burl = '';
        }
        if ($burl != '')
            $banner['burl'] = $burl;
        else
            $banner['burl'] = '';
        return $banner;
    }

}

?>