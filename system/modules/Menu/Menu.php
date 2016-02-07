<?php
/**
 *  Name:    Menu
 *  Type:    Model
 *  About:   Модель базы данных - Пункт меню
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Menu;

class Menu extends \Control\Model
{
    public $id;
    public $name;
    public $title;
    public $module;
    public $options;
    public $parent;
    public $weight;
    public $level;
    public $data;
    public $page;
    public $childs;
    public $active;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Menu\Entities\Menu');
    }
    
    // Сохранить
    public function save($data, $id = null)
    {
        if(!empty($id))
            $item = $this->find($id);
        
        if(empty($item))
        {
            $item = new \Menu\Entities\Menu;
            
            $item->title  = '';
            $item->module  = '';
            $item->options = '';
            $item->parent  = 0;
            $item->weight  = 0;
            $item->level   = 1;
            $item->data    = '';
            $item->page    = '';
            $item->childs  = '';
            $item->active  = 1;
        }
        
        foreach($data as $field => $value)
            $item->$field = $value;
        
        if (empty($item->title))
            $item->title = $item->name;
        
        $this->em->persist($item);
        $this->em->flush();
        
        if (empty($item->weight))
        {
            $item->weight = $item->id;
            
            $this->em->persist($item);
            $this->em->flush();
        }
        
        /* ToDo
         *
         * Добавление соответствующего разрешения в модуль Users
         */
        
        return $item->id;
    }
    
    // Удалить
    public function delete($id)
    {
        parent::delete($id);
        
        /* ToDo
         *
         * Удалить соответствующее разрешении в модуле Users
         */
    }
}