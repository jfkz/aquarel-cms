<?php
/**
 *  Module:  Users
 *  About:   Модель - Группа пользователей
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Users;

class UserGroup extends \Control\Model
{
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Users\Entities\UserGroup');
    }
    
    public function save($data, $permissions_active = array(), $id = null)
    {
        if(!empty($id))
            $group = $this->find($id);
        
        if(empty($group))
        {
            $group = new \Users\Entities\UserGroup;
            
            $group_max = $this->repository->findBy(array(), array('id' => 'DESC'));
            $group->id = (!empty($group_max))
                ? $group_max[0]->id * 2
                : 1
            ;
        }
        
        foreach($data as $field => $value)
            $group->$field = $value;
        
        $this->em->persist($group);
        $this->em->flush();
        
        // Permissions
        $permissions = $this->em->getRepository('Users\Entities\UserPermission')->findBy(array());
        foreach ($permissions as $permission)
        {
            $access = ($permission->access_mask & $id);
            
            // need to enable
            if(in_array($permission->id, $permissions_active) && !$access)
            {
                $permission->access_mask += $id;
                
                $this->em->persist($permission);
                $this->em->flush();
            }
            
            // need to disable
            elseif(!in_array($permission->id, $permissions_active) && $access)
            {
                $permission->access_mask-= $id;
                
                $this->em->persist($permission);
                $this->em->flush();
            }
        }
        
        return $group->id;
    }
}