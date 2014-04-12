<?php
define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(ROOT_PATH),
    get_include_path(),
)));

require ROOT_PATH."/lib/CreateSEFU.class.php";
$sefu = new CreateSEFU();
$sefu->applySEFU();

//require $_SERVER['DOCUMENT_ROOT']."/lib/SEFUfromHtaccess.class.php";
//$obj = new SEFUfromHtaccess();
//$obj->apply();
?>
