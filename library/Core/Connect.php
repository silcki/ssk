<?php
class Core_Connect
{
    protected $_db;

    public function __construct()
    {
        $this->_db = Zend_Registry::get('db');

//        $this->_db->getConnection()->exec("SET character_set_server = utf8");
//        $this->_db->getConnection()->exec("SET NAMES utf8");
//        $this->_db->getConnection()->exec("SET CHARACTER SET utf8");
//        $this->_db->getConnection()->exec("SET character_set_connection = utf8");
//        $this->_db->getConnection()->exec('SET OPTION CHARACTER SET utf8');
    }
}