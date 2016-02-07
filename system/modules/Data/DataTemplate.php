<?php
/**
 * Module:  Data
 * About:   Модель - Шаблон данных
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Data;

class DataTemplate extends \Control\Model
{
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Data\Entities\DataTemplate');
    }
    
    // Сохранить
    public function save($data, $id = null)
    {
        if(!empty($id))
            $item = $this->find($id);
        
        if(empty($item))
        {
            $item = new \Data\Entities\DataTemplate;
            
            $item->num      = 0;
            $item->template = '';
        }
        
        foreach($data as $field => $value)
            $item->$field = $value;
        
        $this->em->persist($item);
        $this->em->flush();
        
        return $item->id;
    }
}