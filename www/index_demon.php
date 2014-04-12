<?php

header('Content-type: text/html; charset=UTF-8');
set_time_limit(0);
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
require_once ROOT_PATH . '/include/config.php';

set_include_path(ROOT_PATH . PATH_SEPARATOR . ZEND_PATH);

require_once ZEND_PATH . '/Search/Lucene/Analysis/Analyzer.php';

require_once ZEND_PATH . '/Loader.php';

Zend_Loader::loadClass('Zend_Config_Ini');
Zend_Loader::loadClass('Zend_Registry');
Zend_Loader::loadClass('Zend_Search_Lucene');
Zend_Loader::loadClass('Zend_Db');
Zend_Loader::loadClass('Zend_Db_Table');
Zend_Loader::loadClass('Zend_Db_Expr');

try {
    $config = new Zend_Config_Ini(ROOT_PATH . '/config/config.ini.php', 'general');
    $registry = Zend_Registry::getInstance();
    $registry->set('config', $config);
    $db = Zend_Db::factory($config->db->adapter, $config->db->config->toArray());
    Zend_Db_Table::setDefaultAdapter($db);

    $db->getConnection()->exec("SET character_set_server = utf8");
    $db->getConnection()->exec("SET NAMES utf8");
    $db->getConnection()->exec("SET CHARACTER SET utf8");
    $db->getConnection()->exec("SET character_set_connection = utf8");
    $db->getConnection()->exec('SET OPTION CHARACTER SET utf8');
} catch (Zend_Db_Adapter_Exception $e) {
    echo "возможно, неправильные параметры соединения или СУРБД не запущена";
    exit;
} catch (Zend_Exception $e) {
    echo "возможно, попытка загрузки требуемого класса адаптера потерпела неудачу";
    exit;
}

Zend_Search_Lucene_Analysis_Analyzer::setDefault(new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8());

$map = new SearchIndex($db);
$map->run();

class SearchIndex
{

    private $_db;
    private $index = '';
    private $lang_url = '';

    function __construct($db)
    {
        $this->_db = $db;

        Zend_Search_Lucene_Analysis_Analyzer::setDefault(
                new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8Num_CaseInsensitive());
    }

    function run()
    {
        $langs = $this->getLangs();
        if (!empty($langs)) {
            foreach ($langs as $ln) {
                if ($ln['IS_DEFAULT'] == 0)
                    $this->lang_url = '/' . $ln['SYSTEM_NAME'];
                else
                    $this->lang_url = '';

                if (strtolower($ln['SYSTEM_NAME']) != 'ru') {
                    $lang_id = $ln['CMF_LANG_ID'];
                }
                else
                    $lang_id = 0;

                $index_path = INDEX_PATH . $ln['SYSTEM_NAME'];
                $this->recursive_remove_directory($index_path, TRUE);

                try {
                    $this->index = Zend_Search_Lucene::create($index_path);
                    chmod($index_path, 0777);
                } catch (Zend_Search_Lucene_Exception $e) {
                    echo "<p class=\"ui-bad-message\">Не удалось создать поисковой индекс: {$e->getMessage()}</p>";
                }

                $this->catalogueProcessing($lang_id);
                $this->itemProcessing($lang_id);
                $this->anotherPagesProcessing($lang_id);

                $this->index->optimize();
            }
        }
    }

    function recursive_remove_directory($directory, $empty = FALSE)
    {
        if (substr($directory, -1) == '/') {
            $directory = substr($directory, 0, -1);
        }
        if (!file_exists($directory) || !is_dir($directory)) {
            return FALSE;
        } elseif (is_readable($directory)) {
            $handle = opendir($directory);
            while (FALSE !== ($item = readdir($handle))) {
                if ($item != '.' && $item != '..') {
                    $path = $directory . '/' . $item;
                    if (is_dir($path)) {
                        self::recursive_remove_directory($path);
                    } else {
                        unlink($path);
                    }
                }
            }
            closedir($handle);
            if ($empty == FALSE) {
                if (!rmdir($directory)) {
                    return FALSE;
                }
            }
        }
        return TRUE;
    }

    private function catalogueProcessing($lang_id)
    {
        $catalogs = $this->getCatTree($lang_id);
        if (!empty($catalogs)) {
            foreach ($catalogs as $k => $view) {
                $children_item_count = $this->getItemsCount($view['CATALOGUE_ID']);

                $href = '';
                if (($view['ITEM_IS_DESCR'] == 1) && ($children_item_count == 1)) {
                    $item_id = $this->getCatFirstItems($view['CATALOGUE_ID']);
                    $href = $this->lang_url . '/cat/item/n/' . $view['CATALOGUE_ID'] . '/it/' . $item_id . '/';
                } elseif ($children_item_count > 0) {
                    $href = $this->lang_url . '/cat/view/n/' . $view['CATALOGUE_ID'] . '/';
                } else {
                    if (!empty($view['URL'])) {
                        $href = $view['URL'];
                    } elseif (!empty($view['REALCATNAME']) && $view['REALCATNAME'] != '/') {
                        $href = $this->lang_url . '/cat' . $view['REALCATNAME'];
                    } else {
                        $href = $this->lang_url . '/cat/' . $view['CATALOGUE_ID'] . '/';
                    }
                }

                $section = 'catalog';
                $title = mb_convert_case($view['NAME'], MB_CASE_LOWER, 'UTF-8');

                $view['DESCRIPTION'] = strip_tags($view['DESCRIPTION']);
                $content = mb_convert_case($view['DESCRIPTION'], MB_CASE_LOWER, 'UTF-8');

                $doc = new Zend_Search_Lucene_Document();
                $doc->addField(Zend_Search_Lucene_Field::UnIndexed('url', $href));
                $doc->addField(Zend_Search_Lucene_Field::UnIndexed('catalogue_id', $view['CATALOGUE_ID']));
                $doc->addField(Zend_Search_Lucene_Field::Text('title', $title, 'UTF-8'));
                $doc->addField(Zend_Search_Lucene_Field::Text('content', $content, 'UTF-8'));
                $doc->addField(Zend_Search_Lucene_Field::Text('search_section', $section));
                $this->index->addDocument($doc);
            }
        }
    }

    private function anotherPagesProcessing($lang_id)
    {
        $another_pages = $this->getTree($lang_id);

        if (!empty($another_pages)) {
            foreach ($another_pages as $view) {
                $url = '';
                if (!empty($view['URL'])) {
                    if (strpos($view['URL'], 'http://') !== false) {
                        $url = $view['URL'];
                    }
                    else
                        $url = $this->lang_url . $view['URL'];
                }
                elseif (!empty($view['REALCATNAME']) && $view['REALCATNAME'] != '/') {
                    $url = $this->lang_url . '/doc' . $view['REALCATNAME'];
                } else {
                    $url = $this->lang_url . '/doc/' . $view['ANOTHER_PAGES_ID'] . '/';
                }

                $section = 'another_pages';
                $title = mb_convert_case($view['NAME'], MB_CASE_LOWER, 'UTF-8');
                $content = mb_convert_case($view['NAME'], MB_CASE_LOWER, 'UTF-8');

                $doc = new Zend_Search_Lucene_Document();
                $doc->addField(Zend_Search_Lucene_Field::UnIndexed('url', $url));
                $doc->addField(Zend_Search_Lucene_Field::Text('title', $title));
                $doc->addField(Zend_Search_Lucene_Field::Text('content', $content));
                $doc->addField(Zend_Search_Lucene_Field::Text('search_section', $section));
                $this->index->addDocument($doc);
            }
        }
    }

    private function itemProcessing($lang_id)
    {
        $items = $this->getItems($lang_id);
        if (!empty($items)) {
            foreach ($items as $view) {
                $href = $this->lang_url . '/cat/item/n/' . $view['CATALOGUE_ID'] . '/it/' . $view['ITEM_ID'] . '/';

                $section = 'item';
                $doc = $this->getDocXml($view['ITEM_ID'], 3, $lang_id);
                $doc - strip_tags($doc);
                $title = mb_convert_case($view['CATALOGUE_NAME'] . ' ' . $view['NAME'], MB_CASE_LOWER, 'UTF-8');
                $content = mb_convert_case($doc, MB_CASE_LOWER, 'UTF-8');

                $doc = new Zend_Search_Lucene_Document();
                $doc->addField(Zend_Search_Lucene_Field::UnIndexed('url', $href));
                $doc->addField(Zend_Search_Lucene_Field::UnIndexed('item_id', $view['ITEM_ID']));
                $doc->addField(Zend_Search_Lucene_Field::Text('title', $title, 'UTF-8'));
                $doc->addField(Zend_Search_Lucene_Field::Text('content', $content, 'UTF-8'));
                $doc->addField(Zend_Search_Lucene_Field::Text('search_section', $section));
                $this->index->addDocument($doc);
            }
        }
    }

    private function getLangs()
    {
        $sql = "select SYSTEM_NAME
                 , CMF_LANG_ID
                 , IS_DEFAULT
            from CMF_LANG
            where STATUS=1";

        return $this->_db->fetchAll($sql);
    }

    private function getCatTree($lang)
    {
        if ($lang > 0) {
            $sql = "select A.CATALOGUE_ID
                    ,A.PARENT_ID
                    ,A.CATNAME
                    ,A.REALCATNAME
                    ,A.URL
                    ,A.ITEM_IS_DESCR
                    ,B.NAME
                    ,B.DESCRIPTION
              from CATALOGUE A
              inner join CATALOGUE_LANGS B on B.CATALOGUE_ID=A.CATALOGUE_ID
              where A.REALSTATUS=1
                and B.CMF_LANG_ID={$lang}
              order by A.ORDERING";
        } else {
            $sql = "select CATALOGUE_ID
                    ,PARENT_ID
                    ,CATNAME
                    ,NAME
                    ,DESCRIPTION
                    ,REALCATNAME
                    ,URL
                    ,ITEM_IS_DESCR
              from CATALOGUE
              where REALSTATUS=1
              order by ORDERING";
        }

        return $this->_db->fetchAll($sql);
    }

    private function getTree($lang)
    {
        if ($lang > 0) {
            $sql = "select A.ANOTHER_PAGES_ID
                    , A.CATNAME
                    , A.REALCATNAME
                    , A.URL
                    , B.NAME
                from ANOTHER_PAGES A
                inner join ANOTHER_PAGES_LANGS B on B.ANOTHER_PAGES_ID=A.ANOTHER_PAGES_ID
                where A.STATUS=1
                  and B.CMF_LANG_ID={$lang}
                order by ORDER_ asc";
        } else {
            $sql = 'select ANOTHER_PAGES_ID
                    , CATNAME
                    , REALCATNAME
                    , URL
                    , NAME
               from ANOTHER_PAGES
               where STATUS=1
               order by ORDER_ asc';
        }

        $menu = $this->_db->fetchAll($sql);

        $pos = 2;
        for ($i = 0; $i < count($menu); $i++) {
            $menu[$i]['CATNAME'] = str_replace("/", "", $menu[$i]['CATNAME']);
        }

        return $menu;
    }

    private function getItems($lang)
    {
        if ($lang > 0) {
            $sql = "select A.ITEM_ID
                   , A.CATALOGUE_ID
                   , B.NAME
                   , CL.NAME as CATALOGUE_NAME
              from ITEM A
                  ,ITEM_LANGS B
                  ,CATALOGUE C
                  ,CATALOGUE_LANGS CL
              where B.ITEM_ID=A.ITEM_ID
                and A.CATALOGUE_ID = C.CATALOGUE_ID
                and C.CATALOGUE_ID = CL.CATALOGUE_ID
                and A.STATUS=1
                and B.CMF_LANG_ID={$lang}
                and CL.CMF_LANG_ID={$lang}";
        } else {
            $sql = "select I.ITEM_ID
                   , I.CATALOGUE_ID
                   , I.NAME
                   , C.NAME as CATALOGUE_NAME
              from ITEM I
                  ,CATALOGUE C
              where I.STATUS=1
                and I.CATALOGUE_ID = C.CATALOGUE_ID";
        }

        return $this->_db->fetchAll($sql);
    }

    private function getDocXml($id, $type, $lang)
    {
        $sql = "select XML
          from XMLS
          where XMLS_ID={$id}
            and TYPE={$type}
            and CMF_LANG_ID = {$lang}";

        return $this->_db->fetchOne($sql);
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

}

?>
