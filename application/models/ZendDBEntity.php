<?php

/**
 * Адаптер подключения к БД без применения Zend_Table
 */
class ZendDBEntity
{

    protected $_db;

    function __construct()
    {
        $this->_db = self::getDbConnectSingleton();

        // TODO: такой расклад не годится. Надо сделать отдельную статичную страницу
        // которую будем показывать при невозможном подключении к БД
        // правильно будет отпарвлять на ошибку 503 - типа сервак временно недоступен
        if (!$this->_db) {
//      throw new Exception ("Cant connect to DB", 503);
//      return false;

            $front = Zend_Controller_Front::getInstance();
            $front->setControllerDirectory(APPLICATION_CONTROLLERS);

//      require_once(APPLICATION_PATH.'/../Zend/View/Abstract.php');
//      require_once(APPLICATION_PATH.'/../Zend/Controller/Action/Helper/ViewRenderer.php');
//      require_once(APPLICATION_PATH.'/../include/View_Xslt.php');
//      $view = new View_Xslt;

            $options = array();
            $viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer($view, $options);
            $viewRenderer->setViewSuffix('xsl');
            Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);

            $req = new Zend_Controller_Request_Http();
            // Меняем URL на который нам нужно для вывода ошибки
            $req->setRequestUri('/error/error/');
            $errors->type = 503;

            $req->setParam('error_handler', $errors);
            $front->setRequest($req);


            // Запускаем диспетчер чтобы вызвать контроллер обработки ошибки
            $front->dispatch();
            return false;
//      die();
//      throw new Exception("Cant connect to DB", 503);
//      $request = $front->getRequest();
//      $ff = $dispatcher->getResponse();
        }
//  ->getResponse();
        //->setHttpResponseCode('404');
//  $URI = new Zend_Controller_Request_Http();
//  $path = $URI->getRequestUri();

        $this->_db->getConnection()->exec("SET character_set_server = utf8");
        $this->_db->getConnection()->exec("SET NAMES utf8");
        $this->_db->getConnection()->exec("SET CHARACTER SET utf8");
        $this->_db->getConnection()->exec("SET character_set_connection = utf8");
        $this->_db->getConnection()->exec('SET OPTION CHARACTER SET utf8');
    }

    static function getDbConnectSingleton()
    {
        $registry = Zend_Registry::getInstance();
        if (Zend_Registry::isRegistered('db_connect'))
            return $registry->get('db_connect');

        try {
            $config = new Zend_Config_Ini('config/config.ini.php', 'general');
            $db = Zend_Db::factory($config->db->adapter,
                                   $config->db->config->toArray());

            // Пробуем получить соединение.
            // Если такое не произойдёт то вызывает Exception типа Zend_Db_Adapter_Exception
            $db->getConnection();

            $registry->set('db_connect', $db);
            return $db;
        } catch (Zend_Db_Adapter_Exception $e) {
            $registry->_unsetInstance('db_connect');
            echo $e->getMessage();
            return false;
        }
    }

}