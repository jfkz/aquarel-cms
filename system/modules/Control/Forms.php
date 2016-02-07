<?php
/**
 * Class:   Forms
 * File:    class.forms.php
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Control;

class Forms
{
    private $type;
    private $title;
    private $content = array();
    
    public function __construct() { }
    
    public function setTitle($title)
    {
        $this->content['title'] = $title;
    }
    
    public function setTemplate($template)
    {
        if ($template != '') $this->content['template'] = $template;
    }
    
    public function addHead($head)
    {
        $this->content['head'] = $head;
    }
    
    public function addRows($rows)
    {
        $this->content['rows'] = $rows;
    }
    
    public function addHTML($text)
    {
        $this->content[] = array('type' => 'html',
                                 'text' => $text);
    }
    
    public function addFooter($obj)
    {
        $this->content['footer'][] = $obj;
    }
    
    public function addObject($obj)
    {
        if (isset($obj->status) && $obj->status == 'db')
            $obj = $this->objectDb($obj);
        
        if (!empty($obj->type))
            $this->content[] = $obj;
    }
    
    public function addPager($pages, $active, $page)
    {
        $this->content['pager'] = array('page'   => $page,
                                        'pages'  => $pages,
                                        'active' => $active);
    }
    
    public function addDraggebleTree($tree,$properties) /* дерево drag-n-drop */
    {
        $this->content['tree']['branches']   = $tree;
        $this->content['tree']['properties'] = $properties;
    }
    
    public function addPortlets($columns,$columns_title)
    {
        $this->content['portlets']['columns']       = $columns;
        $this->content['portlets']['columns_title'] = $columns_title;
    }
    
    public function addTab($title)
    {
        if ($title != '')
            $this->content['tabs'][] = array('title' => $title);
    }
    
    public function setTabTemplate($template)
    {
        if ($template != '')
        {
            $count = count($this->content['tabs']) - 1;
            $this->content['tabs'][$count]['template'] = $template;
        }
    }
    
    public function addTabRows($rows)
    {
        $count = count($this->content['tabs']) - 1;
        $this->content['tabs'][$count]['rows'] = $rows;
    }
    
    public function addTabObject($obj)
    {
        if (isset($obj->status) && $obj->status == 'db')
            $obj = $this->objectDb($obj);
        
        if (!empty($obj->type))
        {
            $count = count($this->content['tabs']) - 1;
            $this->content['tabs'][$count][] = $obj;
        }
    }
    
    public function addTabHTML($text)
    {
        $count = count($this->content['tabs']) - 1;
        
        $this->content['tabs'][$count][] = array(
            'type' => 'html',
            'text' => $text
        );
        
    }
    
    public function objectDb($obj)
    {
        $conf = Core::conf();
        $em   = Core::em();
        
        switch ($obj->type)
        {
            case 'select':
            {
                $options = unserialize($obj->options);
                
                $module = $options['module'];
                $entityName = $options['entity'];
                $index = $options['index'];
                $title = $options['title'];
                
                $entityName = '\\'. $module .'\\Entities\\'. $entityName;
                $entity = $em->getRepository($entityName);
                
                $obj->options = array();
                
                if (empty($options['where']))
                    $list = $entity->findBy(array());
                else
                {
                    if (empty($options['order']))
                        $list = $entity->findBy($options['where']);
                    else
                        $list = $entity->findBy($options['where'], $options['order']);
                }
                
                foreach ($list as $item)
                {
                    $obj->options[$item->$index] = $item->$title;
                }
                
                break;
            }
            case 'checkbox_list':
            {
                $options = unserialize($obj->options);
                
                $module = $options['module'];
                $entityName = $options['entity'];
                $index = $options['index'];
                $title = $options['title'];
                
                $values = (isset($obj->value)) ? explode(',', $obj->value) : array();
                
                $obj->items = array();
                $i = 1;
                
                $entityName = '\\'. $module .'\\Entities\\'. $entityName;
                $Entity = $em->getRepository($entityName);
                
                $list = $Entity->findBy(array());
                foreach ($list as $item)
                {
                    $obj->items[] = array('type'      => 'checkbox',
                                            'id'        => $item->$index . $i,
                                            'value'     => $item->$title,
                                            'class'     => 'checkbox',
                                            'attributes'=> (in_array($item->$title, $values)) ? 'checked' : '',
                                            'label'     => $item->$title );
                    
                    $i++;
                }
                
                break;
            }
        }
        
        return $obj;
    }
    
    public function render()
    {
        $output = json_encode($this->content);
        
        return $output;
    }
}