<?php
/**
 *  Class:   Page
 *  About:   Модель - Страница
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Pages;

class Page extends \Control\Model
{
    public $id;
    public $name;
    public $title;
    public $route_id;
    public $parent;
    public $weight;
    public $meta_title;
    public $template;
    public $text;
    public $show_in_menu;
    public $active;
    
    protected $conf;
    protected $em;
    protected $repository;
    
    public $path = '';
    public $level = 0;
    public $parents = array();
    
    public $url;
    public $query;
    
    private $data = array();
    private $site = array();
    private $page = array();
    private $js   = array();
    
    public function __construct()
    {
        parent::__construct();
        
        $this->repository = $this->em->getRepository('Pages\Entities\Page');
    }
    
    public function findByRequest($request)
    {
        $request_count = count($request);
        
        if($request_count == 1 && empty($request[0]))
            $request[0] = 'home';
        
        $parent = 0;
        for ($i = 0; $i < $request_count; $i++) {
            
            $page = $this->repository->findOneBy(array(
                'parent' => $parent,
                'name'   => $request[$i]
            ));
            
            if ($page) 
                $parent = $page->id;
            else
                return false;
        }
        
        return $page;
    }
    
    public function getTree()
    {
        $tree = $this->getChilds(0);
        
        return $tree;
    }
    
    public function save($data, $id = null)
    {
        if(!empty($id))
            $item = $this->find($id);
        
        if(empty($item))
        {
            $item = new \Pages\Entities\Page;
            
            $item->title = '';
            $item->meta_title = '';
            $item->text = '';
            $item->parent = 0;
            $item->weight = 0;
            $item->show_in_menu = 0;
            $item->active = 0;
        }
        
        foreach($data as $field => $value)
            $item->$field = $value;
        
        $this->em->persist($item);
        $this->em->flush();
        
        if (empty($item->weight))
        {
            $item->weight = $item->id;
            
            $this->em->persist($item);
            $this->em->flush();
        }
        
        return $item->id;
    }
    
    private function getChilds($pageId = null)
    {
        $childs = null;
        
        if(!isset($pageId))
            $pageId = $this->id;
        
        $pages = $this->repository->findBy(
            array('parent' => $pageId),
            array('weight' => 'ASC')
        );
        
	if (!empty($pages) && is_array($pages))
        {
            $childs = array();
            
            foreach($pages as $page)
            {
                $childs[] = array(
                    'id'     => $page->id,
                    'title'  => $page->title,
                    'childs' => $this->getChilds($page->id)
                );
            }
	}
        
        return $childs;
    }
}