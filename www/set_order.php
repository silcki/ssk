<?php

set_time_limit(0);
header('Content-type: text/html; charset=UTF-8');
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

define('ERROR_YANDEX_XML', 1);

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
define('ZEND_PATH', ROOT_PATH . '/Zend');
set_include_path(ROOT_PATH . PATH_SEPARATOR . ZEND_PATH);

require_once ZEND_PATH . '/Loader.php';

Zend_Loader::loadClass('Zend_Config_Ini');
Zend_Loader::loadClass('Zend_Registry');
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

$sql = "select distinct ITEM_ID
      from ITEM_PHOTO";

$item_id = $db->fetchCol($sql);

if (!empty($item_id)) {
    foreach ($item_id as $iid) {
        $sql = "select ITEM_PHOTO_ID
          from ITEM_PHOTO
          where ITEM_ID = ?
          order by ITEM_PHOTO_ID";

        $item_photo_id = $db->fetchCol($sql, $iid);

        $ordering = 1;
        if (!empty($item_photo_id)) {
            foreach ($item_photo_id as $ipid) {
                $sql = "update ITEM_PHOTO
              set ORDERING_ = {$ordering}
              where ITEM_PHOTO_ID = {$ipid}";

//        echo $sql.';<br>';

                $db->query($sql);

                $ordering++;
            }
        }
    }
}
?>