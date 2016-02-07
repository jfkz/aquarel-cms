<?php
/**
 *  Name:    Pages
 *  Type:    Controller
 *  About:   Класс управления страницами
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Pages;

class Controller extends \Control\ControllerAncestor
{
    private $repPage;
    
    public $id          = 0;
    public $name        = '';
    public $title       = '';
    public $path        = '';
    public $level       = 0;
    public $parents     = array();
    public $parent      = 0;
    
    public $url;
    public $query;
    
    public function __construct()
    {   
        parent::__construct();
        
        $this->repPage = $this->em->getRepository('Pages\Entities\Page');
    }
    
    /*
     *  SiteActions
     */
    
    public function indexAction()
    {
        $live = \Control\Live::getInstance();
        
        $live->_data_need_theme = true;
        
        $this->level = count($live->_request)-1;
        $this->name  = $live->_request[$this->level];
        
        /* ToDo
         *
         * Добавить выбор главной страницы в настройки
         * Если мы находимся на главной странице, значение $this->name брать из настроек
         */
        if(empty($this->name))
            $this->name = 'home';
        
        $this->path = $live->_request;
        
        $pages = new \Pages\Page;
        $page = $pages->findByRequest($live->_request);
        
        if (!$page && empty($live->_data))
            $page = $this->repPage->findOneBy(array('name' => '404'));
        
        if($page)
        {
            $this->data['content']    = htmlspecialchars_decode($page->text);
            $this->data['title']      = $page->title;
            $this->data['meta_title'] = (!empty($page->meta_title))
                ? $page->meta_title
                : $page->title
            ;
            
            if(empty($live->_theme_template) && !empty($page->template))
                $live->_theme_template = $page->template;
        }
        
        if (empty($live->_data['meta_title']) && empty($this->data['meta_title']) && !empty($live->_data['title']))
            $this->data['meta_title'] = $live->_data['title'];
        
        $this->data['js'] = $this->getJs();
        
        $this->data['site'] = array(
            'theme_path' => '/custom/themes/'. $this->conf->setting('site_theme'),
            'name'       => $this->conf->site_name,
            'slogan'     => $this->conf->site_slogan
        );
        
        return $this->data;
    }
    
    /*
     *  ControlActions
     */
    
    // Структура страниц
    public function listAction()
    {
        $live = \Control\Live::getInstance();
        
        $page = new \Pages\Page;
        $tree = $page->getTree();
        
        $form = new \Control\Forms;
        $form->setTitle('Страницы');
        $form->setTemplate('tree');
        
        $properties = array('animate'           => 'true',
                            'autoclose'         => 'false',
                            'docToFolderConvert'=> 'true',
                            'afterMove'         => "if ($('span:first',destination).parent().attr('id') && $('span:first',source).parent().attr('id')) {
                                                        var parent = $('span:first',destination).parent().attr('id').split('_')['1'];
                                                        var id = $('span:first',source).parent().attr('id').split('_')['1'];
                                                        pages_reorder(parent,id,pos);
                                                        }",
                            'afterDblClick'     => "var id = $('span:first',node).parent().attr('id').split('_')['1']; location.href = '#Pages/edit/'+id;",
                            'afterClick'        => ''
                            );
        
        $form->addDraggebleTree($tree, $properties);
        
        $output = $form->render();
        
        return $output;
    }
    
    public function optionsAction()
    {
        $live = \Control\Live::getInstance();
        
        $edit_page = '';
        if ($live->user->access(array('module'=>'pages', 'page'=>'edit')))
            $edit_page = 'edit';
        elseif ($live->user->access(array('module'=>'pages', 'page'=>'edit_content')))
            $edit_page = 'edit_content';
        
        $json = array();
        if ($live->user->access(array('module'=>'pages', 'page'=>'add')))
            $json[] = array('title'=>'Добавить страницу', 'action'=>'pages_add()');
        
        if ($edit_page != '')
        {
            $childs = array();
            
            $pages = $this->repPage->findBy(
                array('parent' => 0),
                array('weight' => 'ASC')
            );
            
            if(!empty($pages))
            {
                foreach ($pages as $page)
                {
                    $childs[] = array('title'=>$page->title, 'href'=>'#Pages/'. $edit_page .'/'. $page->id);
                    
                    $page_childs = $this->repPage->findBy(
                        array('parent' => $page->id),
                        array('weight' => 'ASC')
                    );
                    
                    if(!empty($page_childs))
                        foreach ($page_childs as $page_child)
                            $childs[] = array('title'=>'&nbsp;&nbsp;&nbsp;'. $page_child->title, 'href'=>'#Pages/'. $edit_page .'/'. $page_child->id);
                }
            }
            
            $json[] = array('title'=>'Список страниц', 'href'=>'#Pages/list', 'childs'=>$childs);
        }
        
        // $json[] = array('title'=>'Настройки', 'href'=>'#Pages/settings');
        
        $output = json_encode($json);
        
        return $output;
    }
    
    // Добавление страницы
    public function addAction()
    {
        $live = \Control\Live::getInstance();
        
        $i=1;
        $name = 'new';
        $title = 'Новая страница';
        
        $page = $this->repPage->findOneBy(array('name' => $name));
        
        while(!empty($page))
        {
            $i++;
            $name = 'new-'. $i;
            $title = 'Новая страница ('. $i .')';
            
            $page = $this->repPage->findOneBy(array('name' => $name));
        }
        
        $data = array(
            'name' => $name,
            'title' => $title,
            'meta_title' => '',
            'template' => 'main',
            'text' => '',
        );
        
        $pages = new \Pages\Page;
        $pages->save($data);
    }
    
    // Форма редактирования страницы
    public function editAction($id = null)
    {
        $id = intval($id);
        
        $data = new \Data\Data;
        $data->setName('Page');
        
        $output = $data->editForm(array('title' => 'Редактировать страницу'), $id);
        
        return $output;
    }
    
    // Сохранить
    public function saveAction($data_type, $id = null)
    {
        $data = array();
        
        $fields = explode($this->conf->ac_sep, $_POST['fields']);
        $values = explode($this->conf->ac_sep, $_POST['values']);
        
        foreach($fields as $key => $field)
            $data[$field] = $values[$key];
        
        $page = new \Pages\Page;
        $page->save($data, $id);
    }
    
    // Удалить
    public function deleteAction($data_name, $id)
    {
        $id = intval($id);
        
        $page = new \Pages\Page;
        $page->delete($id);
    }
    
    public function reorderAction($id)
    {
        $id = intval($id);
        
        if (empty($id))
            return false;
        
        $values = explode($this->conf->ac_sep, $_POST['values']);
        $parent = intval($values[0]);
        $position = intval($values[1]);
        
        $page_model = new \Pages\Page;
        $page_model->save(array('parent' => 100500), $id);
        
        $pages = $this->repPage->findBy(
            array('parent' => $parent),
            array('weight' => 'ASC')
        );
        
        $weight = ($position > 0 && isset($pages[$position-1])) ? $pages[$position-1]->weight + 1 : 1;
        
        $page = $this->repPage->findOneBy(array('weight' => $weight));
        if (!empty($page))
        {
            foreach ($pages as $page)
            {
                if ($page->weight >= $weight)
                {
                    $page->weight+= 1;
                    
                    $this->em->persist($page);
                    $this->em->flush();
                }
                
            }
        }
        
        $data = array(
            'parent' => $parent,
            'weight' => $weight
        );
        
        $page_model = new \Pages\Page;
        $page_model->save($data, $id);
    }


    /*
     *  Service methods
     */

    private function getJs()
    {
        $js = array();
        
        if (file_exists($this->conf->root_dir .'/custom/javascripts/jquery.js'))
            $js[] = '/custom/javascripts/jquery.js';
        
        // подключение пользовательских javascript-файлов
        if ($handle = opendir($this->conf->root_dir .'/custom/javascripts/'))
        {
            while (($file = readdir($handle)) !== false)
            {
                $ext = explode('.', $file);
                
                if ($ext[1] == 'js' && $file != 'jquery.js')
                    $js[] = '/custom/javascripts/'. $file;
            }
            
            closedir($handle);
        }
        
        // подключение javascript-файлов из пользовательских модулей
        $modules = $this->em->getRepository('Modules\Entities\Module')->findBy(array('active' => 1, 'group' => 'custom'));
        foreach($modules as $module)
        {
            $filename = '/custom/modules/'. $module->name .'/'. $module->name .'.js';
            
            if (file_exists($this->conf->root_dir . $filename))
                $js[] = $filename;
        }
        
        return $js;
    }
}