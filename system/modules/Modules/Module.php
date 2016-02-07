<?php
/**
 *  Name:    Module
 *  About:   Модель данных
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Modules;

class Module extends \Control\Model
{
    public $id = 0;
    public $name = '';
    public $title = '';
    public $group = 'system';
    public $active = 0;
    public $install = 0;
    public $version = '';
    
    public $info;
    
    public function __construct($name = null, $load_data = true)
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Modules\Entities\Module');
        
        if(empty($name))
            return 'Модуль не найден';
        
        $this->name = $name;
        $this->group = $this->getGroup();
        
        if(empty($this->group))
            return 'Модуль не найден';
        
        $this->info = $this->getInfo();
        
        if ($load_data)
        {
            $module = $this->find(array('name' => $this->name));
            
            if (empty($module))
                return 'Модуль не найден';
            
            $this->id      = $module->id;
            $this->title   = $module->title;
            $this->group   = $module->group;
            $this->active  = $module->active;
            $this->install = $module->install;
            $this->version = $module->version;
        }
    }
    
    // Установка модуля
    public function install()
    {
        if($this->install)
            return 'Модуль уже установлен';
        
        if (empty($this->info))
            return 'Модуль не найден';
        
        // Fill in the table menu
        $this->fillMenu();
        
        // Fill in the content tables
        $this->fillContent();
        
        // Информация о модуле
        $this->name    = $this->name;
        $this->title   = $this->info->title;
        $this->group   = $this->group;
        $this->version = $this->info->version;
        $this->install = 1;
        $this->active  = 1;
        
        $this->save();
    }
    
    // Удаление модуля
    public function uninstall()
    {
        $con = $this->em->getConnection();
        
        if ($this->group != 'system')
        {
            // Удаление типов данных модуля
            $this->deleteDataTypes();
            
            // Удаление модуля из меню системы управления
            $this->deleteMenu();
            
            /* Удаление модуля из списка разрешений */
            $this->deleteContent();
            
            /* Удаление таблиц модуля */
            if (!empty($this->info->tables) && is_array($this->info->tables))
                foreach ($this->info->tables as $table_name => $table_fields)
                    $con->executeQuery("DROP TABLE IF EXISTS `{$this->conf->db_prefix}{$table_name}`");
            
            /* Удаление информации о модуле из базы */
            $this->delete();
        }
        else
        {
            echo 'system!!!';
        }
    }
    
    public function save()
    {
        if(!empty($this->id))
            $item = $this->find($this->id);
        
        if(empty($item))
        {
            $item = new \Modules\Entities\Module;
            
            $item->caption = '';
        }
        
        $vars = get_object_vars($this);
        
        foreach($vars as $field => $value)
            $item->$field = $value;
        
        $this->em->persist($item);
        $this->em->flush();
        
        return $item->id;
    }
    
    // Добавление таблиц модуля в базу
    public function createTables()
    {
        $con = $this->em->getConnection();
        
        $mysql_types = array('varchar','tinyint','text','date','smallint','mediumint','int','bigint','float','double','decimal','datetime','timestamp','time','year','char','tinyblob','tinytext','blob','mediumblob','mediumtext','longblob','longtext','enum','set','bit','bool','binary','varbinary');
        
        if (!empty($this->info->tables) && is_array($this->info->tables))
        {
            foreach ($this->info->tables as $table_name => $table_fields)
            {
                $sql = "CREATE TABLE IF NOT EXISTS `{$this->conf->db_prefix}". \Control\Core::quote_smart($table_name) ."` (";
                $fields = array();
                foreach ($table_fields as $field_name => $field_info)
                {
                    // Название поля и тип
                    $field_type = (in_array($field_info['type'], $mysql_types)) ? $field_info['type'] : 'text';
                    $field = "`". \Control\Core::quote_smart($field_name) ."` {$field_type}";
                    
                    // Длина/значения
                    if (isset($field_info['length']))
                    {
                        $field_length = intval($field_info['length']);
                        $field.= "(". $field_length .")";
                    }
                    
                    // not_null
                    if (isset($field_info['not_null']) && $field_info['not_null'] == 1)
                        $field.= " not null";
                    
                    // default
                    if (isset($field_info['default']) && $field_info['default'] != '')
                        $field.= " default '". \Control\Core::quote_smart($field_info['default']) ."'";
                    
                    // auto_increment
                    if (isset($field_info['auto_increment']) && $field_info['auto_increment'] == 1)
                        $field.= " auto_increment";
                    
                    $fields[] = $field;
                }
                $sql.= implode(',', $fields); 
                
                $sql.= ", PRIMARY KEY (`id`) ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
                
                try {
                    $con->executeQuery($sql);
                } catch (Exception $e) {
                    echo $this->name.'. Ошибка:<br/>'. $e .'<br/>'. $sql .'<br/><br/>';
                }
            }
        }
    }
    
    public function createDataTypes()
    {
        if (!empty($this->info->data_types) && is_array($this->info->data_types))
        {
            foreach ($this->info->data_types as $data_type_name => $type)
            {
                $data = array(
                    'name'   => $data_type_name,
                    'title'  => $type['title'],
                    'entity' => $type['entity'],
                    'module' => $this->name,
                    'active' => 1,
                );
                
                $data_type = new \Data\DataType;
                $data_type->save($data);
                
                if (!empty($type['fields']))
                {
                    foreach ($type['fields'] as $field_name => $field)
                    {
                        $data = array(
                            'name'          => $field_name,
                            'title'         => $field['title'],
                            'module'        => $this->name,
                            'data_name'     => $data_type_name,
                            'type'          => $field['type'],
                            'options'       => (isset($field['options'])) ? $field['options'] : '',
                            'attributes'    => (isset($field['attributes'])) ? $field['attributes'] : '',
                            'default_value' => (isset($field['default_value'])) ? $field['default_value'] : '',
                            'status'        => (isset($field['status'])) ? $field['status'] : '',
                            'active'        => 1,
                        );
                        
                        $data_field = new \Data\DataField;
                        $data_field->save($data);
                    }
                }
            }
        }
    }
    
    public function deleteDataTypes()
    {
        if (!empty($this->info->data_types) and is_array($this->info->data_types))
        {
            foreach ($this->info->data_types as $data_type_name => $type)
            {
                $data = array(
                    'name'   => $data_type_name,
                    'entity' => $type['entity'],
                    'module' => $this->name,
                );
                
                $data_type = new \Data\DataType;
                $data_type->delete($data);
                
                if (!empty($type['fields']))
                {
                    foreach ($type['fields'] as $field_name => $field)
                    {
                        $data = array(
                            'name'      => $field_name,
                            'module'    => $this->name,
                            'data_name' => $data_type_name,
                        );
                        
                        $data_field = new \Data\DataField;
                        $data_field->delete($data);
                    }
                }
            }
        }
    }
    
    public function fillMenu()
    {
        if (!empty($this->info->menu) && is_array($this->info->menu))
        {
            foreach ($this->info->menu as $item)
            {
                if (!empty($item['childs']))
                {
                    $childs = $item['childs'];
                    unset($item['childs']);
                }
                
                $item['level'] = 0;
                
                $menu_item = new \Menu\Menu;
                $menu_item_id = $menu_item->save($item, array('name' => $item['name']));
                
                if (!empty($childs))
                {
                    foreach ($childs as $child)
                    {
                        $child['module'] = $this->name;
                        $child['parent'] = $menu_item_id;
                        
                        $menu_child = new \Menu\Menu;
                        $menu_child->save($child);
                    }
                }
            }
        }
    }
    
    public function deleteMenu()
    {
        if (!empty($this->info->menu) && is_array($this->info->menu))
        {
            foreach ($this->info->menu as $item)
            {
                if (!empty($item['childs']))
                {
                    foreach ($item['childs'] as $child)
                    {
                        $data['name'] = $child['name'];
                        $data['module'] = $this->name;
                        
                        $menu_child = new \Menu\Menu;
                        $menu_child->delete($data);
                    }
                }
            }
        }
    }
    
    public function fillContent()
    {
        if (!empty($this->info->content) && is_array($this->info->content))
        {
            $data_types = array();
            
            $result = $this->em->getRepository('Data\Entities\DataType')->findBy(array());
            foreach ($result as $data_type)
                $data_types[$data_type->name] = $data_type;
            
            foreach ($this->info->content as $data_type => $items)
            {
                $module = $data_types[$data_type]->module;
                $class = '\\'. $module .'\\'. $data_type;
                
                $item = new $class;
                $fields = array_keys(get_class_vars('\\'. $module .'\Entities\\'. $data_types[$data_type]->entity));
                
                foreach ($items as $item_data)
                {
                    if (in_array('module', $fields))
                        $item_data['module'] = $this->name;
                    
                    $item->save($item_data);
                }
            }
        }
    }
    
    public function deleteContent()
    {
        if (!empty($this->info->content) && is_array($this->info->content))
        {
            $data_types = array();
            
            $result = $this->em->getRepository('Data\Entities\DataType')->findBy(array());
            foreach ($result as $data_type)
                $data_types[$data_type->name] = $data_type;
            
            foreach ($this->info->content as $data_type => $items)
            {
                $module = $data_types[$data_type]->module;
                $class = '\\'. $module .'\\'. $data_type;
                
                $item = new $class;
                $fields = array_keys(get_class_vars('\\'. $module .'\Entities\\'. $data_types[$data_type]->entity));
                
                foreach ($items as $item_data)
                {
                    if (in_array('module', $fields))
                        $item_data['module'] = $this->name;
                    
                    $item->delete($item_data);
                }
            }
        }
    }
    
    public function setActive($value)
    {
        $active = intval($value);
        
        $this->active = $active;
        
        $access_mask = ($active == 0) ? 0 : 1;
        
        $perm = new \Users\UserPermission;
        $permissions = $perm->getListByModule($this->name);
        foreach($permissions as $permission)
        {
            $permission->access_mask = $access_mask;
         
            $this->em->persist($permission);
            $this->em->flush();   
        }
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getActive()
    {
        return $this->active;
    }
    
    public function getInstall()
    {
        return $this->install;
    }
    
    public function checkActionExists($action)
    {
        $result = false;
        
        if (!empty($this->id) && !empty($action))
        {
            $obj_name = '\\'. $this->name .'\\Controller';
            
            $result = method_exists($obj_name, $action);
        }
        
        return $result;
    }
    
    private function getInfo()
    {
        $live = \Control\Live::getInstance();
        
        $info = array();
        
        $file_path = $this->conf->root_dir .'/'. $this->group .'/modules/'. $this->name .'/info.php';
        
        if (file_exists($file_path))
            $info = require($file_path);
        
        return $info;
    }
    
    private function getGroup()
    {
        $group = '';
        
        if (file_exists($this->conf->root_dir .'/system/modules/'. $this->name .'/info.php'))
            $group = 'system';
        elseif (file_exists($this->conf->root_dir .'/custom/modules/'. $this->name .'/info.php'))
            $group = 'custom';
        
        return $group;
    }
}