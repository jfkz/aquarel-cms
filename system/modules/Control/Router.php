<?php
/**
 * Class:   Router
 * About:   Маршрутизатор системы
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Router;

class Router extends \Control\Model
{
    protected $repository;
    
    public $route       = '';
    public $controller  = '';
    public $action      = '';
    
    private $ancestors  = array();
    
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Control\Entities\Route');
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
    /*
    public function findByRequest($route)
    {
        require_once('system/models/model.route.php');
        
        /*
        $qb = $this->db->createQueryBuilder();
        $result = $qb->add('select', 'u')
            ->add('from', 'Route u')
            ->add('orderBy', 'u.route ASC')
            ->getQuery()
            ->getResult();
        *
        /*
        $routes = $this->db->getRepository('Route')->findBy(array('route' => 'test1'));
        foreach($routes as $route) {
            $route->controller = 'test';
            $route->action = 'test';
            $route->template = 'test';
            
            $this->db->persist($route);
        }
        $this->db->flush();
        *
        
        // Если маршрут пустой или содержит только один пустой элемент,
        // ищем маршрут "/home"
        $this->route = (!empty($route) && !($route[0] == '' && count($route) == 1)) ? $route : array(0 => 'home');
        $this->getAncestors();
        
        $query = "SELECT * FROM {$this->conf->db_prefix}routes WHERE `route`='%s'";
        
        $i = 0;
        $result = false;
        while(!$result && $i < count($this->route))
        {
            $result = $this->db->Execute(sprintf($query, $this->ancestors[$i]))->fields;
            
            $i++;
        }
        
        if($result)
        {
            $this->controller = $result['controller'];
            $this->action     = $result['action'];
            
            return true;
        }
        else
            return false;
    }
        
    private function getAncestors()
    {
        $path   = $this->route;
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
    }*/
}