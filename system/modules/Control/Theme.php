<?php
/**
 * Class:   Theme
 * About:   Фабрика по производству тем для сайта
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Control;

class Theme
{
    private $conf;
    private $smarty;
    
    public $name;
    public $path;
    
    public $template_index = 'index1';
    public $template_default = 'main1';
    
    public function __construct() { }
    
    public static function getFactory()
    {
        $conf = Core::conf();
        $live = Live::getInstance();
        
        $theme_name = $live->_theme;
        
        if (empty($theme_name))
        {
            if(empty($live->_theme_control))
                $live->_theme_control = (!empty($live->_referer) && $live->_referer[0] == 'Control');
            
            /* ToDo
             *
             * Добавить выбор темы панели управления в настройки
             * Если _use_control_theme = true, значение $live->_theme брать из настроек
             */
            if($live->_theme_control)
                $live->_theme = $theme_name = 'sky';
            else
                $live->_theme = $theme_name = $conf->setting('site_theme');
        }
        
        $theme_file_name = 'theme.'. $theme_name .'.php';
        
        if(!empty($live->_data) && is_array($live->_data))
        {
            if($live->_theme_control)
                $live->_data['site']['theme_path'] = '/system/themes/'. $theme_name;
            else
                $live->_data['site']['theme_path'] = '/custom/themes/'. $theme_name;
        }
        
        $paths = array(
            'system/themes/'. $theme_name,
            'custom/themes/'. $theme_name,
        );
        
        $path = $live->findPath($paths, $theme_file_name);
        
        if (!empty($path))
        {
            include_once($path);
            
            $theme = $theme_name .'Theme';
            $theme = new $theme();
            $theme->setName($theme_name);
        }
        else
        {
            $theme = new Theme();
        }
        
        return $theme;
    }
    
    public function init()
    {
        $conf = Core::conf();
        $live = Live::getInstance();
        
        if(empty($live->_theme_control))
            $live->_theme_control = (!empty($live->_referer) && $live->_referer[0] == 'Control');
        
        /* ToDo
         *
         * Добавить выбор темы панели управления в настройки
         * Если _use_control_theme = true, значение $live->_theme брать из настроек
         */
        if($live->_theme_control)
            $live->_theme = $theme_name = 'sky';
        else
            $live->_theme = $theme_name = $conf->setting('site_theme');
    }
    
    public function getList()
    {
        $this->conf = Core::conf();
        
        $list = array(
            'system' => array(),
            'custom' => array(),
        );
        
        $handle = opendir($this->conf->root_dir .'/system/themes/');
        while (($file = readdir($handle)) !== false)
            if (strpos($file, '.') === false)
                $list['system'][] = $file;
        
        $handle = opendir($this->conf->root_dir .'/custom/themes/');
        while (($file = readdir($handle)) !== false)
            if (strpos($file, '.') === false)
                $list['custom'][] = $file;
        
        return $list;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        $this->path = Core::conf()->root_dir .'/custom/themes/'. $name;
    }
    
    public function templateData()
    {
        $live = live::getInstance();
        $this->smarty = Core::smarty();
        
        $controller = $live->_controllers[count($live->_controllers) - 1];
        $action = $live->_actions[count($live->_actions) - 1];
        
        $paths = array(
            'custom/themes/'. $this->name .'/templates/'. $controller,
            'system/themes/'. $this->name .'/templates/'. $controller,
            'custom/modules/'. $controller .'/templates',
            'system/modules/'. $controller .'/templates',
        );
        
        if (!empty($action))
            $path = $live->findPath($paths, $action .'.tpl');
        
        if (!empty($live->_data_template))
        {
            $paths = array(
                'custom/themes/'. $this->name .'/templates/'. $controller,
                'system/themes/'. $this->name .'/templates/'. $controller,
                'custom/themes/'. $this->name .'/templates',
                'system/themes/'. $this->name .'/templates',
                'custom/modules/'. $controller .'/templates',
                'system/modules/'. $controller .'/templates',
            );
            
            $path = $live->findPath($paths, $live->_data_template .'.tpl');
        }
        
        $output = (!empty($path))
            ? $this->smarty->fetch($path)
            : null
        ;
        
        return $output;
    }
    
    public function themeData()
    {
        $live = live::getInstance();
        $this->smarty = Core::smarty();
        
        $templates_paths = array(
            'custom/themes/'. $this->name,
            'system/themes/'. $this->name,
        );
        
        if(!empty($live->_theme_template))
            $path = $live->findPath($templates_paths, $live->_theme_template .'.tpl');
        
        if(empty($path) && !empty($this->template_default))
            $path = $live->findPath($templates_paths, $this->template_default .'.tpl');
        
        if(empty($path))
            $path = $live->findPath($templates_paths, 'main.tpl');
        
        $output = (!empty($path))
            ? $this->smarty->fetch($path)
            : null
        ;
        
        return $output;
    }
    
    public function buildBlocks()
    {
        $live = live::getInstance();
        $this->smarty = Core::smarty();
        
        $blocks = array();
        
        // Getting list of blocks
        $blocks_obj = new \Blocks\Block;
        $blocks_data = $blocks_obj->getList();
        
        foreach($blocks_data as $block)
        {
            $template_file_name = $block['name'] .'.tpl';
            
            // Getting block's data
            $model = '\\'. $block['module'] .'\\Controller';
            $model = new $model;
            
            $action = $block['name'] .'Block';
            
            if($block_output = $model->$action())
                foreach($block_output as $name => $data)
                    $this->smarty->assign($name, $data);
            
            // Templating block's data
            $paths = array(
                'custom/themes/'. $this->name .'/templates/'. $block['module'],
                'system/themes/'. $this->name .'/templates/'. $block['module'],
                'custom/modules/'. $block['module'] .'/templates',
                'system/modules/'. $block['module'] .'/templates',
                'custom/modules/'. $block['module'] .'/templates',
            );
            $path = $live->findPath($paths, $template_file_name);
            
            if(!empty($path))
            {
                $output = $this->smarty->fetch($path);
                
                if (empty($blocks[$block['region']]))
                    $blocks[$block['region']] = $output;
                else
                    $blocks[$block['region']].= $output;
            }
        }
        
        return $blocks;
    }
    
    public function displayBlock($block)
    {
        $class_name = get_class($block);
        
        if ($class_name == 'Block')
        {
            $output = array();
            
            foreach($block->Display() as $block)
                $output[$block->name] = $this->displayBlock($block);
        }
        else
        {
            $output = '';
            $method = 'display'. $class_name;
            $output.= $this->$method($block);
        }
        
        return $output;
    }
    
    public function displayBlockList($block)
    {
        $output = '<ul name="'. $block->name .'">';
        
        foreach ($block->Display() as $item_name => $item)
        {
            $output.= '<li name="'. $item_name .'">';
            
            if(is_object($item))
                $output.= $this->displayBlock($item);
            else
                $output.= $item;
            
            $output.= '</li>';
        }
        $output.= '</ul>';
        
        return $output;
    }
}