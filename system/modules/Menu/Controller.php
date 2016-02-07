<?php
/**
 *  Name:    Menu
 *  Type:    Controller
 *  About:   Меню панели управления - контроллер
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Menu;

class Controller extends \Control\ControllerAncestor
{
    
    public function __construct()
    {
        parent::__construct();
        
        $this->repMenu = $this->em->getRepository('Menu\Entities\Menu');
    }
    
    /*
     *  Actions
     */
    
    // Структура меню
    public function listAction()
    {
        $live = \Control\Live::getInstance();
        
        $live->_data_template = 'portlets';
        $live->_data_need_template = true;
        
        $this->data['title'] = 'Меню панели управления';
        
        $columns        = array();
        $columns_titles = array();
        $columns_ids    = array();
        
        /* Перебор элементов меню верхнего(нулевого) уровня */
        $parents = $this->repMenu->findBy(
            array('level' => 0),
            array('weight' => 'ASC')
        );
        
        foreach ($parents as $parent)
        {
            $columns_ids[] = $parent->id;
            
            $portlets = array();
            
            /* Перебор элементов первого уровня, дочерних для текущего элемента нулевого уровня */
            $childs = $this->repMenu->findBy(
                array('parent' => $parent->id),
                array('weight' => 'ASC')
            );
            
            foreach ($childs as $child)
            {
                $portlet = array(
                    'id'     => $child->id,
                    'title'  => $child->title,
                    'active' => $child->active,
                );
                
                $portlets[] = $portlet;
            }
            
            $column = array(
                'id'       => $parent->id,
                'name'     => $parent->name,
                'title'    => $parent->title,
                'portlets' => $portlets,
            );
            
            $columns[$parent->id] = $column;
        }
        
        $columns_ids = implode($this->conf->ac_sep, $columns_ids);
        
        $this->data['columns'] = $columns;
        $this->data['columns_ids'] = $columns_ids;
        $this->data['columns_titles'] = $columns_titles;
        
        return $this->data;
    }
    
    // Список опций
    public function optionsAction()
    {
        $json = array();
        
        $json[] = array('title'=>'Добавить пункт меню', 'href'=>'#Menu/add');
        $json[] = array('title'=>'Меню', 'href'=>'#Menu/list');
        
        $output = json_encode($json);
        
        return $output;
    }
    
    // Форма добавления пункта меню
    public function addAction()
    {
        $data = new \Data\Data;
        $data->setName('Menu');
        
        $output = $data->addForm(array('title' => 'Добавить пункт меню'));
        
        return $output;
    }
    
    // Форма редактирования пункта меню
    public function editAction($id = null)
    {
        $id = intval($id);
        
        $data = new \Data\Data;
        $data->setName('Menu');
        
        $output = $data->editForm(array('title' => 'Редактировать пункт меню'), $id);
        
        return $output;
    }
    
    // Сохранить
    public function saveAction($data_name, $id = null)
    {
        $data = array();
        
        $fields = explode($this->conf->ac_sep, $_POST['fields']);
        $values = explode($this->conf->ac_sep, $_POST['values']);
        
        foreach($fields as $key => $field)
            $data[$field] = $values[$key];
        
        $menu = new \Menu\Menu;
        $menu->save($data, $id);
    }
    
    // Удалить
    public function deleteAction($data_name, $id)
    {
        $id = intval($id);
        
        $menu = new \Menu\Menu;
        $menu->delete($id);
    }
    
    // Включить
    public function activateAction($data_name, $id)
    {
        $id = intval($id);
        
        $menu = new \Menu\Menu;
        $menu->save(array('active' => 1), $id);
    }
    
    // Выключить
    public function disableAction($data_name, $id)
    {
        $id = intval($id);
        
        $menu = new \Menu\Menu;
        $menu->save(array('active' => 0), $id);
    }
    
    public function pageInfoAction()
    {
        $module_name = (!empty($_POST['module'])) ? \Control\Core::quote_smart($_POST['module']) : '';
        $page_name   = (!empty($_POST['page'])) ? \Control\Core::quote_smart($_POST['page']) : '';

        $info = $this->repMenu->findOneBy(
            array('module' => $module_name, 'page' => $page_name)  // OR `childs` LIKE '%{$page_name}%'
        );

        if (!$info)
            return false;
        
        $info = array(
            'main_page' => $info->page,
            'options' => $info->options,
        );
        
        $output = json_encode($info);
        
        return $output;
    }
    
    public function displayAction()
    {
        $live = \Control\Live::getInstance();
        $core = \Control\Core::getInstance();
        
        $user = $live->user;
        
        $active_module = (!empty($_POST['module']))
            ? $core->quote_smart($_POST['module'])
            : ''
        ;
        
        $active_page = (!empty($_POST['page']))
            ? $core->quote_smart($_POST['page'])
            : ''
        ;
        
        $result = $this->repMenu->findOneBy(array(
            'active' => 1,
            'level' => 1,
            'module' => $active_module,
            'page' => $active_page
        ));
        
        if (empty($result) && !empty($active_page))
        {
            // sql: "SELECT `page` FROM {$this->conf->db_prefix}menu WHERE `active`='1' AND `level`='1' AND `module`='{$active_module}' AND `childs` LIKE '%{$active_page}%'"
            $qb = $this->em->createQueryBuilder();
            $qb -> add('select', 'm')
                -> add('from', '\Menu\Entities\Menu m')
                -> add('where', $qb->expr()->andX(
                    'm.active = 1',
                    'm.level = 1',
                    'm.module = ?1',
                    $qb->expr()->like('m.childs', '?2')
                ))
                ->setParameter(1, $active_module)
                ->setParameter(2, "%".$active_page."%")
            ;
            
            $result = $qb->getQuery()->getResult();
            
            if(!empty($result))
                $active_page = $result[0]->page;
        }
        
        $menu = array();
        $tab_first = true;
        
        $items = $this->repMenu->findBy(
            array('active' => 1, 'level' => 0),
            array('weight' => 'ASC')
        );
        
        foreach ($items as $item)
        {
            $parent_id    = $item->id;
            $parent_title = $item->title;
            $active_tab   = false;
            
            $inner_tab = array();
            
            // sql: "SELECT `page`, `title`, `module`, `data`, `options` FROM {$this->conf->db_prefix}menu WHERE `active`='1' AND `parent`='{$parent_id}' ORDER BY `weight`"
            $childs = $this->repMenu->findBy(
                array('active' => 1, 'parent' => $parent_id),
                array('weight' => 'ASC')
            );
            
            foreach ($childs as $child)
            {
                $module = $child->module;
                $title = $child->title;
                $page =  $child->page;
                $data = ($child->data != '') ? $child->data : 1;
                
                // если есть доступ к странице, формируем ссылку
                if ($user->access(array('module' => $module, 'page' => $page)))
                {
                    $inner_tab_link = array(
                        'id'    => $module .'_'. $page,
                        'href'  => ($data != 1) ? $module .'/'. $page .'/'. $data : $module .'/'. $page,
                        'title' => $title,
                    );
                    
                    if ($page == $active_page && $module == $active_module)
                    {
                        $active_tab = true;
                        $inner_tab_link['active'] = 1;
                    }
                    
                    $inner_tab[] = $inner_tab_link;
                }
            }
            
            // если во вкладке есть элементы меню, записываем ее в массив
            if (count($inner_tab) > 0)
            {
                $inner_tab = array(
                    'id'    => $parent_id,
                    'title' => $parent_title,
                    'items' => $inner_tab,
                );
                
                if ($active_tab)
                    $inner_tab['active'] = 1;
                
                $menu[] = $inner_tab; 
            }
        }
        
        $output = json_encode($menu);
        
        return $output;
    }
    
    public function reorderAction()
    {
        $ids     = explode($this->conf->ac_sep, $_POST['ids']);
        $parents = explode($this->conf->ac_sep, $_POST['parents']);
        $weights = explode($this->conf->ac_sep, $_POST['weights']);
        
        $i = 0;
        foreach ($ids as $id)
        {
            if (isset($weights[$i]) && isset($parents[$i]))
            {
                $id     = intval($id);
                $parent = intval($parents[$i]);
                $weight = intval($weights[$i]);
                
                $menu = $this->em->find('Menu\Entities\Menu', $id);
                if (!empty($menu))
                {
                    $menu->parent = $parent;
                    $menu->weight = $weight;
                    
                    $this->em->persist($menu);
                    $this->em->flush();
                }
            }
            
            $i++;
        }
    }
    
    public function mainBlock()
    {
        $output = array();
        
        $menu = $this->em->getRepository('Pages\Entities\Page')->findBy(
            array('active' => 1, 'show_in_menu' => 1),
            array('weight' => 'ASC')
        );
        
        if(!empty($menu))
        {
            foreach($menu as $item)
            {
                $output[] = array(
                    'title' => $item->title,
                    'link'  => $item->url,
                );
            }
        }
        
        return array(
            'menu' => $output
        );
    }
}