<?php
/**
 * Class:   Smarty
 * About:   Класс подключения к шаблонизатору Smarty
 * File:    class.smarty.php
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

class smarty
{
    protected static $instance;  // object instance
    
    public $adapter;
    
    private $config;
    private $db;
 
    private function __construct()
    {
        $this->config  = config::getInstance();
        $this->db      = db::getInstance();
        
        $this->adapter = new Smarty();
        
        $this->adapter->template_dir   = $this->config->root_dir.'/temp/templates/';
        $this->adapter->compile_dir    = $this->config->root_dir.'/temp/templates_c/';
        $this->adapter->config_dir     = $this->config->root_dir.'/temp/configs/';
        $this->adapter->cache_dir      = $this->config->root_dir.'/temp/cache/';
        $this->adapter->debugging      = $this->config->smarty_debug;
        $this->adapter->caching        = TRUE;
        $this->adapter->cache_lifetime = 30;
        $this->adapter->plugins_dir    = $this->config->root_dir.'/system/lib/Smarty/libs/plugins';
        $this->adapter->compile_check  = TRUE;
        $this->adapter->force_compile  = TRUE;
        
        $this->adapter->register_object('db', $this->db->adapter);
        $this->adapter->register_resource('db', array('smarty_resource_db_source', 'smarty_resource_db_timestamp', 'smarty_resource_db_secure', 'smarty_resource_db_trusted'));
        $this->adapter->register_resource('data', array('smarty_resource_data_source', 'smarty_resource_data_timestamp', 'smarty_resource_data_secure', 'smarty_resource_data_trusted'));
    }
    
    private function __clone() { /* ... */ }
 
    public static function getInstance() {
        return (self::$instance === null) ? 
               self::$instance = new self() :
               self::$instance;
    }
 
    public function doAction() { /* ... */ }
 
}
?>
