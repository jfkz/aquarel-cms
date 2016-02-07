<?php
/**
 * Module:  Data
 * About:   Модель данных
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Data;

class Data extends \Control\Model
{
    public $name;
    public $title;
    public $table;
    public $module;
    public $fields = array();
    public $fields_db;
    public $fields_str;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function setName($name)
    {
        $this->repDataType = $this->em->getRepository('Data\Entities\DataType');
        $this->repDataField = $this->em->getRepository('Data\Entities\DataField');
        
        $dataType = $this->repDataType->findOneBy(array('name' => $name));
        
        if(!empty($dataType))
        {
            $this->name   = $name;
            $this->title  = $dataType->title;
            $this->entity = $dataType->entity;
            $this->module = $dataType->module;
        }
        
        $fields_db = array();
        $fields_str = array();
        
        $fields = $this->repDataField->findBy(
            array('data_name' => $this->name, 'active' => 1),
            array('num' => 'ASC')
        );
        
        foreach ($fields as $field)
        {
            $this->fields[$field->name] = $field;
            
            $fields_db[] = '`'. $field->name .'`';
            $fields_str[] = $field->name;
        }
        
        $this->fields_db  = implode(',', $fields_db);
        $this->fields_str = implode($this->conf->ac_sep, $fields_str);
    }
    
    // Форма добавления материала
    public function addForm($options, $footer='', $form_params=array())
    {
        if (is_array($options))
        {
            if(empty($options['fields']))
                $fields = $this->fields;
            else
            {
                $fields_ = explode($this->conf->ac_sep, $options['fields']);
                
                foreach($fields_ as $field)
                    $fields[] = $this->fields[$field];
            }
            
            $params = (!empty($options['params'])) ? $options['params'] : array();
            $footer = (!empty($options['footer'])) ? $options['footer'] : '';
            $title  = (!empty($options['title']))  ? $options['title']  : '';
        }
        
        $form = new \Control\Forms;
        $form->setTitle($title);
        $form->setTemplate('form');
        
        foreach ($fields as $field)
        {
            $field->id      = $this->module .'_'. $field->name;
            $field->caption = $field->title;
            
            $form->addObject($field);
        }
        
        if ($footer == '')
        {
            $form->addFooter(array(
                'type'    => 'button',
                'id'      => 'ok',
                'value'   => 'Готово',
                'action'  => "save_item('". $this->name ."', '". $this->fields_str ."', 0, 'list');"
            ));
            $form->addFooter(array(
                'type'    => 'button',
                'id'      => 'save',
                'value'   => 'Сохранить',
                'action'  => "save_item('". $this->name ."', '". $this->fields_str ."');"
            ));
            $form->addFooter(array(
                'type'    => 'button',
                'id'      => 'cancel',
                'value'   => 'Отмена',
                'href'    => '#'. $this->module .'/list'
            ));
        }
        else
        {
            if (is_array($footer))
                foreach ($footer as $foot)
                    $form->addFooter($foot); 
            else
                $form->addFooter($footer);
        }
        
        return $form->render();
    }
    
    // Форма редактирования материала
    public function editForm($options, $id, $footer='', $form_params=array())
    {
        $id = intval($id);
        
        if (is_array($options))
        {
            if(empty($options['fields']))
                $fields = $this->fields;
            else
            {
                $fields_ = explode($this->conf->ac_sep, $options['fields']);
                
                foreach($fields_ as $field)
                    $fields[] = $this->fields[$field];
            }
            
            $params = (!empty($options['params'])) ? $options['params'] : array();
            $header = (!empty($options['header'])) ? $options['header'] : '';
            $footer = (!empty($options['footer'])) ? $options['footer'] : '';
            $title  = (!empty($options['title']))  ? $options['title']  : '';
        }
        
        $form = new \Control\Forms;
        $form->setTitle($title);
        $form->setTemplate('form');
        
        if (!empty($header))
            $form->addHTML($header);
        
        $entityName = '\\'. $this->module .'\\Entities\\'. $this->entity;
        
        $item = $this->em->find($entityName, $id);
        foreach ($fields as $field)
        {
            $field_name = $field->name;
            
            $field->id      = $this->module .'_'. $field->name;
            $field->value   = ($field->type != 'password') ? $item->$field_name : '';
            $field->caption = $field->title;
            
            $form->addObject($field);
        }
        
        if ($footer == '')
        {
            $form->addFooter(array('type'    => 'button',
                                   'id'      => 'ok',
                                   'value'   => 'Готово',
                                   'action'  => "save_item('{$this->name}', '{$this->fields_str}', '{$id}', 'list');" ));
            $form->addFooter(array('type'    => 'button',
                                   'id'      => 'save',
                                   'value'   => 'Сохранить',
                                   'action'  => "save_item('{$this->name}', '{$this->fields_str}', '{$id}');" ));
            $form->addFooter(array('type'    => 'button',
                                   'id'      => 'delete',
                                   'value'   => 'Удалить',
                                   'action'  => "javascript: if (MsgOkCancel('Действительно удалить?')) { delete_item('{$this->name}', '{$id}', '1', 'list'); }" ));
            $form->addFooter(array('type'    => 'button',
                                   'id'      => 'cancel',
                                   'value'   => 'Отмена',
                                   'href'    => '#'. $this->module .'/list' ));
        }
        else
        {
            if (is_array($footer))
                foreach ($footer as $foot)
                    $form->addFooter($foot);
            else
                $form->addFooter($footer);
        }
        
        return $form->render();
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getTable()
    {
        return $this->table;
    }
        
    public function getModule()
    {
        return $this->module;
    }
    
    public function getField($field_name)
    {
        return $this->fields[$field_name];
    }
        
    public function getFields_db()
    {
        return $this->fields_db;
    }
    
    public function getFields_str()
    {
        return $this->fields_str;
    }
}