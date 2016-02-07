<?php
/**
 * Class:   Events
 * About:   Модель - Событие
 * Authors: Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Events;

class Event extends \Control\Model
{
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Events\Entities\Event');
    }
    
    public function setName($event)
    {
        // Поиск действий, связанных с данным событием
        $links = $this->repMonitor->findBy(array('event' => $event), array('weight' => 'ASC'));
        
        foreach ($links as $link)
        {
            $className = '\\'. $link->module .'\\'. $link->module;
            
            call_user_func($className .'::'. $link->action, $event);
        }
    }
    
    public function save($data, $id = null)
    {
        if(!empty($id))
            $item = $this->find($id);
        
        if(empty($item))
            $item = new \Events\Entities\Event;
        
        foreach($data as $field => $value)
            $item->$field = $value;
        
        $this->em->persist($item);
        $this->em->flush();
        
        return $item->id;
    }
}