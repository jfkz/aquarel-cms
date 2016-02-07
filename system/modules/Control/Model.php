<?php
/**
 * Class:   Model
 * About:   Класс-предок моделей
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Control;

class Model
{
    protected $conf;
    protected $em;
    protected $repository;
    
    public function __construct()
    {
        $this->conf  = Core::conf();
        $this->em    = Core::em();
    }
    
    public function find($id = null)
    {
        $item = null;
        
        if(!empty($id))
        {
            if (is_array($id))
                $item = $this->repository->findOneBy($id);
            elseif (is_numeric($id))
                $item = $this->repository->find(intval($id));
        }
        
        return $item;
    }
    
    public function delete($id = null)
    {
        if (empty($id) && !empty($this->id))
            $id = $this->id;
        
        if (!empty($id))
            $item = $this->find($id);
        
        if (!empty($item))
        {
            $this->em->remove($item);
            $this->em->flush();
        }
    }
}