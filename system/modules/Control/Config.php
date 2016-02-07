<?php
/**
 * Class:   Config
 * File:    class.config.php
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Control;

class Config
{
    private $properties = array();
    private $settings = array();
    
    public function __construct()
    {
        $path = 'system/configs/';
        
        // Подключение общих настроек сайта
        if (file_exists($path .'config.ini')) 
            $this->properties = parse_ini_file($path .'config.ini');
        
        // Если корневая директория не задана, определяем ее автоматически.
        if (empty($this->properties['root_dir']))
            $this->properties['root_dir'] = realpath(dirname(__FILE__) ."/../../..");
        
        // Подключение настроек для 
        if (file_exists($path.$_SERVER['HTTP_HOST'] .'.ini'))
            $this->properties = array_merge($this->properties, parse_ini_file($path . $_SERVER['HTTP_HOST'] .'.ini'));
        
        foreach($this->properties as $property => $value)
        {
            if ($value == 'false')
                $this->properties[$property] = false;
            elseif ($value == 'true')
                $this->properties[$property] = true;
        }
    }
    
    private function __clone() { }
    
    public function __get($property)
    {
        if(isset($this->properties[$property]))
            return $this->properties[$property];
    }
    
    public function setting($property)
    {
        $em = Core::em();
        
        // Получение настроек из базы данных
        if (empty($this->settings))
        {
            $settings = $em->getRepository('Settings\Entities\Setting')->findBy(array('status' => 1));
            
            foreach ($settings as $setting)
                $this->settings[$setting->name] = $setting->value;
        }
        
        if (isset($this->settings[$property]))
            return $this->settings[$property];
    }
}
?>