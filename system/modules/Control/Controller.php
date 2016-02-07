<?php
/**
 *  Name:    Control
 *  Type:    Controller
 *  About:   Панель управления
 *  Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Control;

class Controller extends ControllerAncestor
{
    
    protected $conf;
    protected $em;
    
    public function __construct()
    {
        parent::__construct();
        
        $live = Live::getInstance();
        
        $live->_theme_control = true;
        
        /* ToDo
         *
         * Добавить выбор темы панели управления в настройки
         * Значение $live->theme брать из настроек
         */
        
        $live->_theme = 'sky';
    }
    
    public function indexAction()
    {
        $live = Live::getInstance();
        
        $live->_data_need_theme = true;
        
        /* ToDo
         *
         * Сделать подключение плагинов модулей
         */
        
        $output = array();
        
        $output['wysiwyg'] = 'ckeditor';
        $output['wysiwyg_skin'] = 'office2003';
        $output['wysiwyg_theme'] = 'Normal';
        
        $output['wysiwyg_load'] = '
            <script type="text/javascript" src="/system/lib/CKEditor/ckeditor.js"></script>
        ';
        $output['wysiwyg_filemanager_load'] = '
            <script type="text/javascript" src="/system/lib/CKEditor/ajexFileManager/ajex.js"></script>
        ';
        
        $output['site']['name'] = $this->conf->site_name;
        $output['cms_version'] = $this->conf->ac_ver;
        $output['server_name'] = $live->_server['SERVER_NAME'];
        
        $output['js_vars'] = '
            <script type="text/javascript" language="javascript">
                var acSep="'. $this->conf->ac_sep .'";
                var acCon="'. $this->conf->ac_con .'";
            </script>
        ';
        
        $js_files = '';
        $modules = $this->em->getRepository('Modules\Entities\Module')->findBy(array('active' => 1), array('group' => 'DESC'));
        foreach($modules as $module)
        {
            $filename = '/'. $module->group .'/modules/'. $module->name .'/js/'. $module->name .'.js';
            
            if (file_exists($this->conf->root_dir . $filename))
                $js_files.= '<script type="text/javascript" src="'. $filename .'"></script>'."\n";
        }
        $output['js_files'] = $js_files;
        
        $user = $live->user;
        
        $user_login = ($user->auth()) ? $user->login : '';
        $output['user_login'] = $user_login;
        
        return $output;
    }
    
    /*
     *  Service methods
     */

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
        
        return ((isset($live->server['HTTP_X_REQUESTED_WITH'])
                 && $live->server['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')
                 || $live->requestType == 'ajaj');
    }
}