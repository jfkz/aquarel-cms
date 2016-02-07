<?php
/**
 * Class:   Setting
 * About:   Модель - Настройка
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Wysiwygs;

class Wysiwyg extends \Control\Model
{
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Wysiwygs\Entities\Wysiwyg');
    }
    
    public function save($data, $id = null)
    {
        if(!empty($id))
            $item = $this->find($id);
        
        if(empty($item))
        {
            $item = new \Wysiwygs\Entities\Wysiwyg;
            
            $item->caption    = '';
            $item->type       = 'textbox';
            $item->value      = '';
            $item->module     = '';
            $item->options    = '';
            $item->attributes = '';
            $item->status     = 1;
            $item->success    = 1;
        }
        
        foreach($data as $field => $value)
            $item->$field = $value;
        
        $this->em->persist($item);
        $this->em->flush();
        
        return $item->id;
    }
}