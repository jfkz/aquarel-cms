<?php
/**
 * Class:   Core
 * About:   Класс-ядро, содержит точки доступа ко всем ключевым элементам системы
 * File:    class.core.php
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Control;

class Core
{
    protected static $instance;
    
    private $conf;      // Конфигурация системы
    private $db;        // Подключение к Базе Данных
    private $em;        // Менеджер сущностей
    private $smarty;    // Подключение к Шаблонизатору
    
    private function __construct() { }
    
    public static function getInstance()
    {
        return (self::$instance === null) ? 
                self::$instance = new self() :
                self::$instance;
    }
    
    public static function init()
    {
        $instance = self::getInstance();
        $conf = self::conf();
        
        require_once($conf->root_dir .'/system/lib/Doctrine/Common/ClassLoader.php');
        
        // Set up class loading
        $classLoader = new \Doctrine\Common\ClassLoader('Doctrine',  $conf->root_dir .'/system/lib');
        $classLoader->register();
        $classLoader = new \Doctrine\Common\ClassLoader('Doctrine\ORM', realpath(__DIR__ . '/../../lib'));
        $classLoader->register();
        $classLoader = new \Doctrine\Common\ClassLoader('Doctrine\DBAL', realpath(__DIR__ . '/../../lib/vendor/doctrine-dbal/lib'));
        $classLoader->register();
        $classLoader = new \Doctrine\Common\ClassLoader('Doctrine\Common', realpath(__DIR__ . '/../../lib/vendor/doctrine-common/lib'));
        $classLoader->register();
        
        // Set up caches
        $config = new \Doctrine\ORM\Configuration;
        $cache = new \Doctrine\Common\Cache\ArrayCache;
        $config->setMetadataCacheImpl($cache);
        $driverImpl = $config->newDefaultAnnotationDriver(array($conf->root_dir .'/system/Entities'));
        $config->setMetadataDriverImpl($driverImpl);
        $config->setQueryCacheImpl($cache);
        
        // Proxy configuration
        $config->setProxyDir($conf->root_dir .'/system/models');
        $config->setProxyNamespace('Proxies');
        
        // Set up logger
        // $logger = new EchoSQLLogger;
        // $config->setSQLLogger($logger);
        
        $config->setAutoGenerateProxyClasses(true);
        
        // Database connection information
        $connectionParams = array(
            'dbname'    => $conf->db_name,
            'user'      => $conf->db_username,
            'password'  => $conf->db_password,
            'host'      => $conf->db_hostname,
            'driver'    => 'pdo_'. $conf->db_driver,
            'collate'   => 'utf8_general_ci', 
            'charset'   => 'utf8',
        );
        
        // Create EntityManager
        $instance->db = \Doctrine\ORM\EntityManager::create($connectionParams, $config);
        
        // Table Prefix
        $evm = new \Doctrine\Common\EventManager;
        $tablePrefix = new \Doctrine\Extensions\TablePrefix($conf->db_prefix);
        $evm->addEventListener(\Doctrine\ORM\Events::loadClassMetadata, $tablePrefix);
        
        // Create EntityManager
        $instance->em = \Doctrine\ORM\EntityManager::create($connectionParams, $config, $evm);
    }
    
    public static function conf()
    {
        $instance = self::getInstance();
        
        if (!isset($instance->conf) || $instance->conf === null)
            return $instance->conf = new \Control\Config;
        else
            return $instance->conf;
    }
    
    public static function db()
    {
        $instance = self::getInstance();
        
        return $instance->db;
    }
    
    public static function em()
    {
        $instance = self::getInstance();
        
        return $instance->em;
    }
    
    public static function smarty()
    {
        $instance = self::getInstance();
        $conf = self::conf();
        
        if (!isset($instance->smarty) || $instance->smarty === null)
        {
            include_once($conf->root_dir .'/system/lib/Smarty/libs/Smarty.class.php');
            
            $smartyConnection = new \smarty();
            
            $smartyConnection->template_dir   = $conf->root_dir .'/temp/templates/';
            $smartyConnection->compile_dir    = $conf->root_dir .'/temp/templates_c/';
            $smartyConnection->config_dir     = $conf->root_dir .'/temp/configs/';
            $smartyConnection->cache_dir      = $conf->root_dir .'/temp/cache/';
            $smartyConnection->debugging      = $conf->smarty_debug;
            $smartyConnection->caching        = TRUE;
            $smartyConnection->cache_lifetime = 30;
            $smartyConnection->plugins_dir    = $conf->root_dir .'/system/lib/Smarty/libs/plugins';
            $smartyConnection->compile_check  = TRUE;
            $smartyConnection->force_compile  = TRUE;
            
            $smartyConnection->registerObject('db', self::db());
            $smartyConnection->registerResource('template', array('smarty_resource_template_source', 'smarty_resource_template_timestamp', 'smarty_resource_template_secure', 'smarty_resource_template_trusted'));
            $smartyConnection->registerResource('data', array('smarty_resource_data_source', 'smarty_resource_data_timestamp', 'smarty_resource_data_secure', 'smarty_resource_data_trusted'));
            
            return $instance->smarty = $smartyConnection;
        }
        else
            return $instance->smarty;
    }
    
    /*
     *  Service methods
     */
    
    // Экранирование переменных
    public function quote_smart($value)
    {
        if (get_magic_quotes_gpc())
            $value = stripslashes($value);
        /*if (!is_numeric($value))
            $value = mysql_real_escape_string($value);*/
        
        $value = htmlspecialchars($value);
        
        return $value;
    }
    
    public static function nameModify($name)
    {
        $name[0] = strtoupper($name[0]);
        return $name;
    }
}