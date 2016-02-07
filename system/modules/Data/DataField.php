<?php
/**
 * Module:  Data
 * About:   Модель - Поле типа данных
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Data;

class DataField extends \Control\Model
{
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Data\Entities\DataField');
    }
    
    // Сохранить
    public function save($data, $id = null)
    {
        if(!empty($id))
            $item = $this->find($id);
        
        if(empty($item))
        {
            $item = new \Data\Entities\DataField;
            
            $item->num = 0;
            $item->options = '';
            $item->template = '';
            $item->attributes = '';
            $item->default_value = '';
            $item->status = '';
            $item->active = 1;
        }
        
        foreach($data as $field => $value)
            $item->$field = $value;
        
        $this->em->persist($item);
        $this->em->flush();
        
        if (empty($item->num))
        {
            $item->num = $item->id;
            
            $this->em->persist($item);
            $this->em->flush();
        }
        
        return $item->id;
    }
}