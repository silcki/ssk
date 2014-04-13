<?php
date_default_timezone_set('Europe/Kiev');

$debug = false;
if ($_SERVER['REMOTE_ADDR'] == '193.138.245.146') {
    $debug = true;
}

ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

define('ROOT_PATH', realpath(dirname(__FILE__) . '/../'));
define('APPLICATION_PATH', ROOT_PATH . '/application');
define('CONFIG_PATH', APPLICATION_PATH . '/configs');
define('LIB_PATH', ROOT_PATH . '/library');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
            realpath(ROOT_PATH),
            realpath(APPLICATION_PATH),
            realpath(CONFIG_PATH),
            realpath(LIB_PATH),
            get_include_path(),
        )));

require_once ROOT_PATH . '/vendor/autoload.php';

/* Zend_Loader_Autoloader */
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

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

require_once ROOT_PATH . '/include/DOMxml.class.php';
require_once APPLICATION_PATH . '/controllers/CommonBaseController.php';


require_once APPLICATION_PATH . '/models/AnotherPages.php';
$AnotherPages = new AnotherPages();

if (!empty($_SERVER['PATH_INFO'])) {
    $_SERVER['REQUEST_URI'] = $_SERVER['PATH_INFO'];
}
$urlInfo = parse_url($_SERVER['REQUEST_URI']); // извлекаем части урла в массив
$_SERVER['REQUEST_URI'] = $urlInfo['path'];

if (strlen($_SERVER['REQUEST_URI']) > 1) {
    $is_page = false;
    $page = 1;
    $pattern_page = '/(.*)page\/(\d*)\//is';
    preg_match($pattern_page, $_SERVER['REQUEST_URI'], $out);

    if (!empty($out[1])) {
        $is_page = true;
        $page = $out[2];
        $_SERVER['REQUEST_URI'] = $out[1];
    }

    $urlInfo = parse_url($_SERVER['REQUEST_URI']); // извлекаем части урла в массив

    $sefuByOld = $AnotherPages->getSefURLbyOldURL($urlInfo['path']); // получаем ЧПУ-урл на основе старого урла

    if (!empty($sefuByOld)) { // если существует ЧПУ-урл для старого урла, делаем 301 редирект со старого урла на ЧПУ
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: $sefuByOld");
        exit();
    } else { // проверяем является ли пришедший урл ЧПУ-урлом из нашей базы
        $siteURLbySEFU = $AnotherPages->getSiteURLbySEFU($urlInfo['path']);

        if (!empty($siteURLbySEFU)) {  // если существует урл сайта для ЧПУ-урла, то формируем $_REQUEST['p_']
            if ($is_page) {
                $siteURLbySEFU.='page/' . $page . '/';
            }

            $_SERVER['REQUEST_URI'] = $siteURLbySEFU;
        }
    }
}

$pattern = '/^\/([a-z]{2})\/?$/Ui';
if (preg_match($pattern, $_SERVER['REQUEST_URI'], $matches)) {
    $_SERVER['REQUEST_URI'] = '/index/index/lng/' . $matches[1];
}

$pattern = '/^\/([a-z]{2})\/(.*)$/Ui';
if (preg_match($pattern, $_SERVER['REQUEST_URI'], $matches)) {
    if (substr($matches[2], -1) == '/') {
        $_SERVER['REQUEST_URI'] = $matches[2] . 'lng/' . $matches[1];
    } else {
        $_SERVER['REQUEST_URI'] = $matches[2] . '/lng/' . $matches[1];
    }
}

Zend_Loader::loadClass('Zend_Controller_Action_Helper_ViewRenderer');

//Front Controller
$front = Zend_Controller_Front::getInstance();

$front->throwExceptions(true);
$front->setControllerDirectory(ROOT_PATH . '/application/controllers');

require_once('include/View_Xslt.php');
$view = new View_Xslt;

$options = array();
/*
 * изменение настроек ViewRenderer:
 * Инстанцировать и зарегистрировать свой объект ViewRenderer,
 * а затем передать его брокеру помощников.
 *
 * Во время инстанцирования контроллера действий производится вызов ViewRenderer
 * для инстанцирования объекта вида. Каждый раз, когда инстанцируется контроллер,
 * вызывается метод init() помощника ViewRenderer, что приводит к установке
 * свойства $view данного контроллера действий и вызову метода addScriptPath()
 * с путем относительно текущего модуля; он будет вызван с префиксом класса,
 * соответствующим имени текущего модуля, что эффективно разделяет пространства имен
 * всех классов помощников и фильтров, определенных для этого модуля.
 */
$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view, $options);
$viewRenderer->setViewSuffix('xsl');
Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

$front->dispatch();