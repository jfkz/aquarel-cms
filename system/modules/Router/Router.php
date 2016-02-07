<?php
/**
 * Class:   Router
 * About:   Маршрутизатор системы
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Router;

class Router extends \Control\Model
{
    public $route       = '';
    public $controller  = '';
    public $action      = '';
    
    private $ancestors  = array();
    
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Router\Entities\Route');
    }
    
    public function findByRequest($request)
    {
        // Если маршрут пустой или содержит только один пустой элемент,
        // ищем маршрут "/home"
        $this->_request = (!empty($request) && !($request[0] == '' && count($request) == 1)) ?
            $request :
            array(0 => 'home')
        ;
        
        $this->getAncestors();
        
        $i = 0;
        $result = false;
        while(!$result && $i < count($this->ancestors))
        {
            $result = $this->repository->findOneBy(array('route' => $this->ancestors[$i]));
            $i++;
        }
        
        return $result;
    }
    
    private function getAncestors()
    {
        $path   = $this->_request;
        $length = count($path);
        $mask   = pow(2, $length-1);
        
        for($i=0; $i<$mask; $i++)
        {
            $ancestor = array();
            $m = base_convert($i, 10, 2);
            
            while(strlen($m) < $length)
                $m = '0'. $m;
            
            for($j=0; $j<$length; $j++)
            {
                if (empty($m{$j}) && isset($path[$j]))
                    $ancestor[] = $path[$j];
                else
                    $ancestor[] = '%';
            }
            
            $this->ancestors[] = implode('/', $ancestor);
        }
    }
    
    // Сохранить
    public function save($data, $id = null)
    {
        if(!empty($id))
            $item = $this->find($id);
        
        if(empty($item))
            $item = new \Router\Entities\Route;
        
        foreach($data as $field => $value)
            $item->$field = $value;
        
        $this->em->persist($item);
        $this->em->flush();
        
        return $item->id;
    }
}