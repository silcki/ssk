<?php

//set_include_path('.'.PATH_SEPARATOR.'application/models'.PATH_SEPARATOR.'application/controllers');
#define('ROOT_PATH', dirname(__FILE__));
#define('ROOT_PATH', '');
#define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
error_reporting(E_ALL & ~E_DEPRECATED);
define('ZEND_PATH', ROOT_PATH . '/Zend');
define('INDEX_PATH', ROOT_PATH . '/index/');

define('SESSION_CAPTCHA_VAR_NAME',  'biz_captcha');

define('DEBUG_MODE', 1);