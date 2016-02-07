<?php
/**
 * Module:  Data
 * About:   Модель - Тип данных
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Data;

class DataType extends \Control\Model
{
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Data\Entities\DataType');
    }
    
    // Сохранить
    public function save($data, $id = null)
    {
        if(!empty($id))
            $item = $this->find($id);
        
        if(empty($item))
        {
            $item = new \Data\Entities\DataType;
            
            $item->num = 0;
            $item->url = '';
            $item->template = '';
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
    
    public function delete($id)
    {
        /* ToDo
         *
         * Удалить все поля этого типа данных
         */
        
        parent::delete($id);
    }
}