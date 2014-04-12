<?php

header('Content-type: text/html; charset=UTF-8');
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

// Set the application root path
define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
define('APPLICATION_MODELS', ROOT_PATH . '/application/models/');
define('APPLICATION_CONTROLLERS', ROOT_PATH . '/application/controllers/');
// Set include path
set_include_path(ROOT_PATH . PATH_SEPARATOR . APPLICATION_MODELS . PATH_SEPARATOR . APPLICATION_CONTROLLERS);


require_once('../include/config.php');
require_once('admin/core.php');
//require_once('include/DOMxml.class.php');

$siteurl = 'http://ssk.ua';
$page_limit = 40000;

$cmf = &new SCMF();
//$domXml = &new DomXML('1.0','UTF-8');

require_once ZEND_PATH . '/Loader.php';

require_once ZEND_PATH . '/Session/Namespace.php';

Zend_Loader::loadClass('Zend_Config_Ini');
Zend_Loader::loadClass('Zend_Registry');

Zend_Loader::loadClass('Zend_View');
Zend_Loader::loadClass('Zend_Db');
Zend_Loader::loadClass('Zend_Db_Table');
Zend_Loader::loadClass('Zend_Feed_Rss');

$config = new Zend_Config_Ini('../config/config.ini.php', 'general');
$registry = Zend_Registry::getInstance();
$registry->set('config', $config);


$db = Zend_Db::factory($config->db->adapter, $config->db->config->toArray());
Zend_Db_Table::setDefaultAdapter($db);
$db->getConnection()->exec("SET character_set_server = utf8");
$db->getConnection()->exec("SET NAMES utf8");
$db->getConnection()->exec("SET CHARACTER SET utf8");
$db->getConnection()->exec("SET character_set_connection = utf8");
$db->getConnection()->exec('SET OPTION CHARACTER SET utf8');

function removeOldRSSnews($db)
{
    $sql = 'DROP TABLE IF EXISTS t0';
    $db->query($sql);

    $sql = 'create temporary table t0 (ITEM_ID int(12) unsigned auto_increment NOT NULL,
   PRIMARY KEY (`ITEM_ID`)) ENGINE = MEMORY';
    $db->query($sql);


    $sql = "insert into t0 select N.NEWS_ID
from  NEWS_GROUP NG JOIN NEWS N using (NEWS_GROUP_ID)
where 1
and NG.RSS <> ''
and DATEDIFF(now(), N.DATA) > 15";

    $db->query($sql);

    $sql = "delete from NEWS
where NEWS_ID in (select * from t0)";
    $db->query($sql);
}

function get_image($url, $max_id)
{
    $exp = substr($url, strrpos($url, '.'));

    $image = $max_id . $exp;

    $handle = fopen($url, "r");
    $contents = '';
    while (!feof($handle)) {
        $contents .= fread($handle, 8192);
    }
    fclose($handle);

    if (!empty($contents)) {
        $filename = 'images/news/' . $image;
        $handle = fopen($filename, 'a');
        fwrite($handle, $contents);
        fclose($handle);

        if (is_file($filename)) {
            $size = getimagesize($filename);
            return $image . "#{$size[0]}#{$size[1]}";
        }
    }
}

removeOldRSSnews($db);

$sql = "select *
      from NEWS_GROUP
      where RSS <> ''";

$rss = $db->fetchAll($sql);

if (!empty($rss)) {
    foreach ($rss as $r) {
        $channel = new Zend_Feed_Rss($r['RSS']);

        if (!empty($channel)) {
            foreach ($channel as $item) {
                $name = $item->title();

                $sql = "select NEWS_ID
              from NEWS
              where NAME = '{$name}'";

                $news_id = $db->fetchOne($sql);

                if (empty($news_id)) {
                    $max_id = $cmf->GetSequence('NEWS');

                    $data['NEWS_GROUP_ID'] = $r['NEWS_GROUP_ID'];
                    $data['NAME'] = $name;
                    $data['DATA'] = date("Y-m-d H:i:s", strtotime($item->pubDate()));
                    $data['URL'] = $item->link();
                    $data['DESCRIPTION'] = $item->description();
                    $data['STATUS'] = 1;

                    if (!empty($item->enclosure['url'])) {
                        $data['IMAGE1'] = get_image($item->enclosure['url'], $max_id);
                    } else {
                        $pattern = '/<img.+src="(.+)".*>/Uis';
                        preg_match_all($pattern, $data['DESCRIPTION'], $out);

                        if (!empty($out[1][0])) {
                            $data['IMAGE1'] = get_image($out[1][0], $max_id);
                            //            $data['DESCRIPTION'] = str_replace($out[0][0],'',$data['DESCRIPTION']);
                            $data['DESCRIPTION'] = strip_tags($data['DESCRIPTION']);
                        }
                    }

                    $db->insert('NEWS', $data);

                    echo "Добавлено успешно - Раздел - " . $r['NAME'] . ' - ' . $data['NAME'] . "<br />";
                }
            }
        }
    }
}