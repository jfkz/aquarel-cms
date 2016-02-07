<?php
/**
 *  Name:    Info
 *  Type:    Controller
 *  About:   Информация о системе
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Info;

class Controller extends \Control\ControllerAncestor
{
    
    public function __construct()
    {
        parent::__construct();
        
        $live = \Control\Live::getInstance();
        $live->_theme_control = true;
    }
    
    
    /*
     *  Actions
     */
    
    public function listAction()
    {
        $form = new \Control\Forms;
        
        $form->setTitle('Информационный модуль');
        $form->setTemplate('list');
        
        $form->addHead(array(
            array('width' => 100),
            array('width' => 300),
        ));
        
        $rows = array();
        
        // версия CMS
        $rows[] = array('Aquarel CMS', $this->conf->ac_ver);
        
        // версия PHP
        $rows[] = array('PHP', phpversion());
        
        // версия GD-библиотеки
        $gd = gd_info();
        $rows[] = array('GD', $gd['GD Version']);
        
        // список установленных модулей
        $modules_installed = array();
        $modules = $this->em->getRepository('Modules\Entities\Module')->findBy(
            array('install' => 1, 'active' => 1, 'group' => 'custom'),
            array('name' => 'ASC')
        );
        foreach($modules as $module)
            $modules_installed[] = $module->name .' ('. $module->title .')';
        
        $rows[] = array('Установленные модули', implode('<br/>', $modules_installed));
        
        $form->addRows($rows);
        
        $output = $form->render();
        
        return $output;
    }
    
    public function optionsAction()
    {
        $json = array();
        
        $json[] = array('title'=>'Список', 'href'=>'#Info/list');
        
        $output = json_encode($json);
        
        return $output;
    }
}