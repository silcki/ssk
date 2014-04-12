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

$params['updateCatalog']['path'] = ROOT_PATH . '/www/images/cat';
$params['updateClients']['path'] = ROOT_PATH . '/www/images/cl';
$params['updateItems']['path'] = ROOT_PATH . '/www/images/it';
$params['updateGallery']['path'] = ROOT_PATH . '/www/images/gallery';
$params['updateGalleryGroup']['path'] = ROOT_PATH . '/www/images/gallery_group';

$UpdateImages = new UpdateImages($db, $params);
$UpdateImages->run();

class UpdateImages
{

    private $db;
    private $params;

    public function __construct($db, $params)
    {
        $this->db = $db;
        $this->params = $params;
    }

    public function run()
    {
        foreach ($this->params as $key => $param) {
            $this->$key($param);
        }
    }

    private function updateCatalog($param)
    {
        $items = $this->getCatalog();
        if (!empty($items)) {
            foreach ($items as $val) {
                $image = explode('#', $val['image']);

                $image_path = $param['path'] . '/' . $image[0];
                if (is_file($image_path)) {
                    $size = getimagesize($image_path);
                    $data['IMAGE1'] = "{$image[0]}#{$size[0]}#{$size[1]}";

                    $this->updateData('CATALOGUE', $data, ' CATALOGUE_ID = ' . $val['id']);
                }
            }
        }
    }

    private function updateClients($param)
    {
        $items = $this->getClients();
        if (!empty($items)) {
            foreach ($items as $val) {
                $image = explode('#', $val['image']);

                $image_path = $param['path'] . '/' . $image[0];
                if (is_file($image_path)) {
                    $size = getimagesize($image_path);
                    $data['IMAGE1'] = "{$image[0]}#{$size[0]}#{$size[1]}";

                    $this->updateData('CLIENT', $data, ' CLIENT_ID = ' . $val['id']);
                }
            }
        }
    }

    private function updateItems($param)
    {
        $items = $this->getItems();
        if (!empty($items)) {
            foreach ($items as $val) {
                $image = explode('#', $val['image']);

                $image_path = $param['path'] . '/' . $image[0];
                if (is_file($image_path)) {
                    $size = getimagesize($image_path);
                    $data['IMAGE'] = "{$image[0]}#{$size[0]}#{$size[1]}";

                    $this->updateData('ITEM', $data, ' ITEM_ID = ' . $val['id']);
                }
            }
        }
    }

    private function updateGallery($param)
    {
        $items = $this->getGallery();
        if (!empty($items)) {
            foreach ($items as $val) {
                $image = explode('#', $val['image']);

                $image_path = $param['path'] . '/' . $image[0];
                if (is_file($image_path)) {
                    $size = getimagesize($image_path);
                    $data['IMAGE1'] = "{$image[0]}#{$size[0]}#{$size[1]}";

                    $this->updateData('GALLERY', $data, ' GALLERY_ID = ' . $val['id']);
                }
            }
        }
    }

    private function updateGalleryGroup($param)
    {
        $items = $this->getGalleryGroup();
        if (!empty($items)) {
            foreach ($items as $val) {
                $image = explode('#', $val['image']);

                $image_path = $param['path'] . '/' . $image[0];
                if (is_file($image_path)) {
                    $size = getimagesize($image_path);
                    $data['IMAGE1'] = "{$image[0]}#{$size[0]}#{$size[1]}";

                    $this->updateData('GALLERY_GROUP', $data, ' GALLERY_GROUP_ID = ' . $val['id']);
                }
            }
        }
    }

    private function getCatalog()
    {
        $sql = "select CATALOGUE_ID as id
               , IMAGE1 as image
          from CATALOGUE
          where IMAGE1 <> ''";

        return $this->db->fetchAll($sql);
    }

    private function getClients()
    {
        $sql = "select CLIENT_ID as id
               , IMAGE1 as image
          from CLIENT
          where IMAGE1 <> ''";

        return $this->db->fetchAll($sql);
    }

    private function getGallery()
    {
        $sql = "select GALLERY_ID as id
               , IMAGE1 as image
          from GALLERY
          where IMAGE1 <> ''";

        return $this->db->fetchAll($sql);
    }

    private function getGalleryGroup()
    {
        $sql = "select GALLERY_GROUP_ID as id
               , IMAGE1 as image
          from GALLERY_GROUP
          where IMAGE1 <> ''";

        return $this->db->fetchAll($sql);
    }

    private function getItems()
    {
        $sql = "select ITEM_ID as id
               , IMAGE as image
          from ITEM
          where IMAGE <> ''";

        return $this->db->fetchAll($sql);
    }

    private function updateData($table, $data, $where)
    {
        $this->db->update($table, $data, $where);

//  $this->db->update('LISTENER', $data, 'LISTENER_ID='.$uid);
    }

}

?>