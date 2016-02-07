<?php
/**
 * Class:   Events
 * About:   Модель - Действие
 * Authors: Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Events;

class Action extends \Control\Model
{
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Events\Entities\Action');
    }
    
    public function save($data, $id = null)
    {
        if(!empty($id))
            $item = $this->find($id);
        
        if(empty($item))
        {
            $item = new \Events\Entities\Action;
            
            $item->active = 1;
        }
        
        foreach($data as $field => $value)
            $item->$field = $value;
        
        $this->em->persist($item);
        $this->em->flush();
        
        return $item->id;
    }
}