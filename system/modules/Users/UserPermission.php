<?php
/**
 *  Module:  Users
 *  About:   Модель - Разрешение
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Users;

class UserPermission extends \Control\Model
{
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Users\Entities\UserPermission');
    }
    
    public function save($data, $id = null)
    {
        if(!empty($id))
            $item = $this->find($id);
        
        if(empty($item))
        {
            $item = new \Users\Entities\UserPermission;
            
            $item->page = '';
            $item->action = '';
            $item->access_mask = 1;
            $item->active = 1;
        }
        
        foreach($data as $field => $value)
            $item->$field = $value;
        
        $this->em->persist($item);
        $this->em->flush();
        
        return $item->id;
    }
    
    public function getListByModule($module)
    {
        $permissions = $this->repository->findBy(array('module' => $module));
        
        return $permissions;
    }
}