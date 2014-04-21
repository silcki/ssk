<?php
class Textes extends Core_Connect
{
    protected $_name = 'TEXTES';

    public function getSysText($indent, $lang = 0)
    {
        if ($lang > 0) {
            $sql = "select B.DESCRIPTION
                 , B.IMAGE
            from TEXTES A inner join TEXTES_LANGS B on B.TEXTES_ID=A.TEXTES_ID
            where A.SYS_NAME = '{$indent}'
              and B.CMF_LANG_ID= {$lang}";
        } else {
            $sql = "select DESCRIPTION
                 , IMAGE
            from TEXTES
            where SYS_NAME= '{$indent}'";
        }

        return $this->_db->fetchRow($sql);
    }

}

?>
