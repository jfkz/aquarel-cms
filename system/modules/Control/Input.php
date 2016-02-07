<?php
/**
 * Class:   Input
 * About:   Класс, содержащий все входные данные
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Control;

class Input
{
    protected static $instance;
    
    private $cookie;
    private $files;
    private $get;
    private $post;
    private $server;
    private $session;
    
    private $array_setters = array('session');                                  // arrays which values can be seted
    
    private function __construct()  { }
    
    public static function init()
    {
        $instance = self::getInstance();
        
        $instance->cookie  = $_COOKIE;
        $instance->files   = $_FILES;
        $instance->get     = $_GET;
        $instance->post    = $_POST;
        $instance->server  = $_SERVER;
        $instance->session = $_SESSION;
    }
    
    public static function getInstance()
    {
        return (self::$instance === null) ? 
                self::$instance = new self() :
                self::$instance;
    }
    
    public static function __callStatic($array_name,$arguments)
    {
        $instance = self::getInstance();
        
        $array_name = strtolower($array_name);
        
        $array = &$instance->$array_name;
        $var   = $arguments[0];
        $value = (isset($arguments[1])) ? $arguments[1] : null;
        
        if (!isset($value))
            return (isset($array[$var]) ) ? $array[$var] : null;
        
        if (in_array($array_name,$instance->array_setters))
        {
            $array[$var] = $value;
            
            switch($array_name)
            {
                case 'session':
                {
                    $_SESSION[$var] = $value;
                    break;
                }
            }
        }
    }
    
}
?>