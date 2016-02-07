<?php
/**
 *  Name:    Settings
 *  Type:    Controller
 *  Module:  Settings - Настройки системы
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Settings;

class Controller extends \Control\ControllerAncestor
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->repSettings = $this->em->getRepository('Settings\Entities\Setting');
    }
    
    /*
     *  Actions
     */
    
    // Форма настроек
    public function listAction()
    {
        $form = new \Control\Forms;
        $form->setTitle('Настройки');
        $form->setTemplate('form');
        
        $theme = new \Control\Theme;
        $themes = $theme->getList();
        
        $settings_save = array();
        
        $settings = $this->repSettings->findBy(
            array(),
            array('id' => 'ASC')
        );
        
        foreach ($settings as $setting)
        {
            if ($setting->name == 'site_theme') 
            {
                $options = array();
                
                foreach($themes['custom'] as $theme)
                    $options[] = $theme .'='. $theme;
                
                $setting->options = implode(',', $options);
            }
            
            $setting->id = 'Settings_'. $setting->name;
            $setting->caption = $setting->title;
            
            $form->addObject($setting);
            
            $settings_save[] = $setting->name;
        }
        
        $form->addFooter(array(
            'type'   => 'button',
            'id'     => 'save',
            'value'  => 'Сохранить',
            'action' => "settings_save('". implode($this->conf->ac_sep, $settings_save) ."');"
        ));
        
        $output = $form->render();
        
        return $output;
    }
    
    // Список опций
    public function optionsAction()
    {
        $json = array();
        
        $json[] = array('title' => 'Список настроек', 'href' => '#Settings/list');
        
        $output = json_encode($json);
        
        return $output;
    }
    
    // Сохранить
    public function saveAction()
    {
        $live = \Control\Live::getInstance();
        
        $fields = explode($this->conf->ac_sep, $live->_post['fields']);
        $values = explode($this->conf->ac_sep, $live->_post['values']);
        
        $setting = new \Settings\Setting;
        
        for ($i=0; $i<count($fields); $i++)
            $setting->save(array('value' => $values[$i]), array('name' => $fields[$i]));
    }   
}