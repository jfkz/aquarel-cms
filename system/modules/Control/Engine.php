<?php
/**
 * Class:   Engine
 * About:   Класс, управляющий жизненным циклом системы
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Control;

class Engine
{   
    public function __construct()
    {
        if (session_id() == '') session_start();
    }
    
    public function init()
    {
        $conf   = Core::conf();
        $em     = Core::em();
        
        $classLoader = new \Doctrine\Common\ClassLoader('Modules\Entities', $conf->root_dir .'/system/modules');
        $classLoader->register();
        
        $modules = $em->getRepository('Modules\Entities\Module')->findBy(array('active' => 1));
        foreach($modules as $module)
        {
            $classLoader = new \Doctrine\Common\ClassLoader($module->name, $conf->root_dir .'/'. $module->group .'/modules');
            $classLoader->register();
            
            $classLoader = new \Doctrine\Common\ClassLoader($module->name. '\Entities', $conf->root_dir .'/'. $module->group .'/modules');
            $classLoader->register();
        }
    }
    
    public function parsing()
    {
        $live = Live::getInstance();
        $conf = Core::conf();
        
        if (session_id() == '') session_start();
        
        $live->_cookie  = $_COOKIE;
        $live->_files   = $_FILES;
        $live->_get     = $_GET;
        $live->_post    = $_POST;
        $live->_server  = $_SERVER;
        $live->_session = $_SESSION;
        
        $request = parse_url($live->_server['REQUEST_URI']);
        $live->_request_path = $request['path'];
        $request_path = explode('/', $request['path']);
        $live->_request = array_slice($request_path, 1, count($request_path));
        
        if(isset($live->_server['HTTP_REFERER']))
        {
            $referer = parse_url($live->_server['HTTP_REFERER']);
            $live->_referer_path = $referer['path'];
            $referer_path = explode('/', $referer['path']);
            $live->_referer = array_slice($referer_path, 1, count($referer_path));
        }
        else
        {
            $live->_referer_path = '';
            $live->_referer = array();
        }
    }
    
    public function routing()
    {
        $live = Live::getInstance();
        
        // Getting arguments
        if(!empty($live->_request[2]))
            for($i=2; $i<count($live->_request); $i++)
                $live->_args[] = $live->_request[$i];
        
        // Finding modules actions
        if(!empty($live->_request[0]))
        {
            $controller_name = $live->_request[0];
            $controller_name[0] = strtoupper($controller_name[0]);
            
            $action_name = (!empty($live->_request[1])) ? $live->_request[1] : 'index';
            
            $module = new \Modules\Module($controller_name, true);
            if ($module->checkActionExists($action_name .'Action'))
            {
                $live->_controllers[] = $controller_name;
                $live->_actions[]     = $action_name;
            }
        }
        
        // Finding routes
        $router = new \Router\Router;
        $findRoute = $router->findByRequest($live->_request);
        if ($findRoute)
        {
            $live->_controllers[] = $findRoute->controller;
            $live->_actions[]     = $findRoute->action;
        }
        
        // Finding pages
        $page = new \Pages\Page;
        $findPage = $page->findByRequest($live->_request);
        if ($findPage)
        {
            $live->_controllers[] = 'Pages';
            $live->_actions[]     = 'index';
        }
    }
    
    public function controlling()
    {
        $live = Live::getInstance();
        
        if(isset($_POST['theme_control']))
            $live->_theme_control = true;
        
        // Getting data of all finding methods
        foreach($live->_controllers as $key => $controller_name)
        {
            $action_name = $live->_actions[$key];
            
            $data = $this->getControllerData($controller_name, $action_name, $live->_args);
            $live->putData($data);
        }
        
        // If some methods need theming data but page not found or no worker was not found
        if ((!in_array('Pages', $live->_controllers) && $live->_data_need_theme) || empty($live->_controllers))
        {
            $data = $this->getControllerData('Pages', 'index', $live->_args);
            $live->putData($data);
        }
    }
    
    public function theming()
    {
        $conf   = Core::conf();
        $smarty = Core::smarty();
        $live   = Live::getInstance();
        $theme  = Theme::getFactory();
        
        if(!empty($live->_data) && is_array($live->_data))
        {
            $blocks = $theme->buildBlocks();
            $live->_data['blocks'] = $blocks;
            
            foreach($live->_data as $name => $data)
                $smarty->assign($name, $data);
        }
        
        if($live->_data_need_template)
        {
            if($live->_data_need_theme)
            {
                $live->_data['content'] = $theme->templateData();
                $smarty->assign('content', $live->_data['content']);
            }
            else
                $live->_data = $theme->templateData();
        }
        
        $live->_output = ($live->_data_need_theme)
            ? $theme->themeData()
            : $live->_data
        ;
    }
    
    public function display()
    {
        $live = Live::getInstance();
        
        $this->sendHeaders();
        
        // Check errors
        $errors = $live->getErrors();
        
        if(!empty($errors))
            echo json_encode($errors);
        elseif(!empty($live->_output))
            echo $live->_output;
        
        exit;
    }
    
    /*
     *  Service methods
     */
    
    private function getControllerData($controller_name, $action, $args)
    {
        $live = Live::getInstance();
        
        // Check user access
        $handler = array(
            'module' => $controller_name,
            'action' => $action,
        );
        
        $action_name = $action .'Action';
        
        if($live->user->access($handler))
        {
            $controller = $live->getController($controller_name);
            $module = new \Modules\Module($controller_name, true);
            
            if(is_object($controller) && $module->checkActionExists($action_name))
                $data = call_user_func_array(array($controller, $action_name), $args);
        }
        else
        {
            $live->error($controller_name, 'Permission denied');
        }
        
        return (!empty($data)) ? $data : null;
    }

    private function sendHeaders()
    {
        if ($this->isXMLHttpRequest())
            header('Content-Type: text/javascript; charset=utf-8');
        else 
            header('Content-Type: text/html; charset=utf-8');
    }
    
    private function isXMLHttpRequest()
    {
        $live = Live::getInstance();
        
        return ((isset($live->_server['HTTP_X_REQUESTED_WITH'])
                 && $live->_server['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
                 || $live->_requestType == 'ajaj');
    }
}