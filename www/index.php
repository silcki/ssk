<?php
date_default_timezone_set('Europe/Kiev');

define('SESSION_CAPTCHA_VAR_NAME',  'biz_captcha');

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
define('APPLICATION_PATH', ROOT_PATH . '/application');
define('CONFIG_PATH', APPLICATION_PATH . '/configs');
define('LIB_PATH', ROOT_PATH . '/library');

if (APPLICATION_ENV != 'production') {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    ini_set("display_startup_errors", 1);
}

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
            realpath(ROOT_PATH),
            realpath(APPLICATION_PATH),
            realpath(CONFIG_PATH),
            realpath(LIB_PATH),
            get_include_path(),
        )));

require_once ROOT_PATH . '/vendor/autoload.php';

$appConfig = new Zend_Config_Yaml(
    CONFIG_PATH . '/application.yml',
    APPLICATION_ENV,
    array('allowModifications'=>true)
);

$appConfig->merge(
    new Zend_Config_Yaml(
        CONFIG_PATH . '/database.yml',
        APPLICATION_ENV,
        array('allowModifications'=>true)
    )
);

$appConfig->merge(
    new Zend_Config_Yaml(
        CONFIG_PATH . '/resources.yml',
        APPLICATION_ENV,
        array('allowModifications'=>true)
    )
);

/** Zend_Application */
require_once 'Zend/Application.php';

$application = new Zend_Application(
    APPLICATION_ENV,
    $appConfig
);

$application->bootstrap();
Zend_Registry::getInstance()->set('bootstrap', $application->getBootstrap());
$application->run();