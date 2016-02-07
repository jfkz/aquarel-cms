<?php
/**
 * Class:   Live
 * About:   Класс, предоставляющий доступ ко всем динамическим объектам системы
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Control;

class Live
{
    protected static $instance;
    
    public $user;   // текущий пользователь системы
    
    private $_errors = array();
    
    public $_cookie;
    public $_files;
    public $_get;
    public $_post;
    public $_server;
    public $_session;
    
    public $_request = array();
    public $_requestType = 'page';
    
    public $_controllers = array();
    public $_actions = array();
    
    public $_theme = '';
    public $_theme_control = false;
    public $_theme_template = '';
    public $_data_need_theme = false;
    public $_data_need_template = false;
    
    public $_args = array();
    public $_data = array();
    public $_output = '';
    
    private function __construct() {
        $this->user = new \Users\User;
    }
    
    public function __call($name, $arguments) { }
    
    public static function getInstance()
    {
        return (self::$instance === null) ? 
                self::$instance = new self() :
                self::$instance;
    }
    
    public function getController($module)
    {
        $class_name = '\\'. $module .'\\Controller';
        
        return new $class_name();
    }
    
    public function putData($data)
    {
        if(empty($data))
            return false;
        
        if(!empty($this->_data) && is_array($this->_data))
        {
            $res = array_merge($data, $this->_data);
            $this->_data = $res;
        }
        else
            $this->_data = $data;
    }
    
    public function event($event_name)
    {
        $conf = core::conf();
        $db   = core::db();
        
        // Поиск действий, связанных с данным событием
        $result = $db->execute("SELECT `module`,`action` FROM {$conf->db_prefix}monitor WHERE `event`='?' ORDER BY `weight`", $event_name);
        while ($result && !$result->EOF)
        {
            $action = $result->fields['action'];
            $module = $result->fields['module'];
            
            $module_group = $db->execute("SELECT `module_group` FROM {$conf->db_prefix}modules WHERE `name`='?'", $module)->fields['group'];
            
            // Запуск действия
            $action_path = $conf->root_dir .'/'. $module_group .'/modules/'. $module .'/class.'. $module .'.php';
            if (file_exists($action_path))
            {
                require_once($action_path);
                call_user_func($module.'::'.$action, $event_name);
            }
            
            $result->MoveNext();
        }
    }
    
    public function error($module, $error)
    {
        $instance = self::getInstance();
        
        if(!isset($instance->errors[$module]))
            $instance->errors[$module] = array();
        
        if (!in_array($error, $instance->errors[$module]))
            $instance->errors[$module][] = $error;
    }
    
    public function getErrors()
    {
        return (!empty($this->errors)) ? array('errors' => $this->errors) : null;
    }
    
    public function findPath($paths, $file_name)
    {
        $conf = core::conf();
        
        $paths_cnt = count($paths);
        
        $i = 0;
        while($i < $paths_cnt && !file_exists($conf->root_dir .'/'. $paths[$i] .'/'. $file_name))
            $i++;
        
        $path = ($i < $paths_cnt) ? $conf->root_dir .'/'. $paths[$i] .'/'. $file_name : null;
        
        return $path;
    }
}