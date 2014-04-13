<?php
class Core_Application_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDatabase(){
        // get config from config/application.ini
        $config = $this->getOptions();

        $db = Zend_Db::factory($config['resources']['db']['adapter'], $config['resources']['db']['params']);

        //set default adapter
        Zend_Db_Table::setDefaultAdapter($db);

        //save Db in registry for later use
        Zend_Registry::set("db", $db);
    }
}