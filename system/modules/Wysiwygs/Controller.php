<?php
/**
 *  Name:    Wysiwygs
 *  Type:    Controller
 *  About:   Управление Wysiwygs
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Wysiwygs;

class Controller extends \Control\ControllerAncestor
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->repWysiwyg = $this->em->getRepository('Wysiwygs\Entities\Wysiwyg');
    }
    
    /*
     *  Actions
     */
    
    // Настройки
    public function settingsAction()
    {
        $conf = \Control\Core::conf();
        
        $form = new \Control\Forms;
        $form->setTitle('Wysiwygs');
        $form->setTemplate('form');
        
        $obj = new \stdClass;
        $obj->type = 'select';
        $obj->id = 'wysiwygs_name';
        $obj->status = 'db';
        $obj->value = (!empty($conf->settings['wysiwyg'])) ? $conf->settings['wysiwyg'] : '';
        $obj->options = 'a:4:{s:6:"module";s:8:"Wysiwygs";s:6:"entity";s:7:"Wysiwyg";s:5:"index";s:4:"name";s:5:"title";s:5:"title";}';
        $obj->caption = 'Визивиг';
        $form->addObject($obj);
        
        $form->addHTML('<div id="wysiwygs_info"><br/><br/><br/></div>');
        
        $form->addFooter(array(
            'type'  => 'button',
            'id'    => 'save',
            'value' => 'Сохранить',
            'action'=> 'wysiwygs_save_settings()'
        ));
        
        $output = $form->render();
        
        return $output;
    }
    
    // Список опций
    public function optionsAction()
    {
        $json = array();
        
        $json = array();
        $json[] = array('title'=>'Настройки', 'href'=>'#wysiwygs/settings');
        
        $output = json_encode($json);
        
        return $output;
    }
}