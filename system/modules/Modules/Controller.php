<?php
/**
 * Class:   Modules
 * About:   Модули системы - контроллер
 * Author:  Vlad Andreev <php.vlad@gmail.com>
 **/

namespace Modules;

class Controller extends \Control\ControllerAncestor
{

    public function __construct()
    {
        parent::__construct();
    }

    public function install($modules_installed)
    {
        $modules_list = $this->getList('system');
        
        if(empty($modules_list))
            return 'Модули не найдены';
        
        $modules = array();
        
        foreach ($modules_list as $module_name => $module)
        {
            $classLoader = new \Doctrine\Common\ClassLoader($module_name, $this->conf->root_dir .'/system/modules');
            $classLoader->register();
        }
        
        foreach ($modules_list as $module_name => $module)
            if (empty($modules_installed) || !in_array($module_name, $modules_installed))
                $modules[] = new \Modules\Module($module_name, false);
        
        $output = '';
        
        if (empty($modules))
            return 'Система уже установлена и готова к работе!';
        
        $output.= '<h3>1. Импорт системных таблиц базы данных</h3>';
        foreach ($modules as $module)
            $module->createTables();
        $output.= 'Успешно.<br/><br/>';
        
        $output.= '<h3>2. Импорт типов данных</h3>';
        foreach ($modules as $module)
            $module->createDataTypes();
        $output.= 'Успешно.<br/><br/>';
        
        $output.= '<h3>3. Установка модулей</h3>';
        foreach ($modules as $module)
            $module->install();
        $output.= 'Успешно.<br/><br/>';
        
        return $output;
    }
    
    /*
     *  Actions
     */
    
    // The list of modules
    public function listAction($group = 'system')
    {
        $img_active    = "<img src='/system/themes/sky/images/active.png' title='выключить' alt='выключить' width='16' height='16' />";
        $img_disable   = "<img src='/system/themes/sky/images/disable.png' title='включить' alt='включить' width='16' height='16' />";
        $img_install   = "<img src='/system/themes/sky/images/install.png' title='Установить' alt='удалить' height='16' width='16' />";
        $img_uninstall = "<img src='/system/themes/sky/images/delete.png' title='удалить' alt='удалить' height='16' width='16' />";
        
        $form = new \Control\Forms;
        $form->setTitle('Модули системы');
        $form->setTemplate('list');
        $form->addHead(array(
            array('width' => 400, 'align' => 'left', 'text' => 'Идентификатор'),
            array('width' => 400, 'align' => 'left', 'text' => 'Имя'),
            array('width' => 50, 'align' => 'left', 'text' => ''),
            array('width' => 200, 'align' => 'left', 'text' => 'Статус'),
        ));
        
        $rows = array();
        
        $modules = $this->getList($group);
        
        if(empty($modules))
            return 'Модули не найдены';
        
        foreach ($modules as $module_name => $module_info)
        {
            $module = new Module($module_name);
            
            $action        = '';
            $action_title  = '';
            $status        = '';
            $status_title  = '';
            
            if (empty($module->name))    $module->name    = ' - ';
            if (empty($module->author))  $module->author  = ' - ';
            if (empty($module->title))   $module->title   = ' - ';
            if (empty($module->version)) $module->version = ' - ';
            
            if(!empty($module->info->dependencies))
            {
                foreach ($module->info->dependencies as $module_dependence_name => $dependence_active)
                {
                    $module_dependence = new Module($module_dependence_name);
                    
                    if ($module_dependence->getActive() == 1)
                        $module_dependencies[$module_dependence_name] = 1;
                    else
                    {
                        $module_dependencies[$module_dependence_name] = 0;
                        $status = 'error_dependencies';
                    }
                }
            }
            
            switch ($module->getInstall())
            {
                // Module is not installed
                case '0':
                {
                    if ($status != 'error_dependencies')
                    {
                        $status_title   = 'не установлен';
                        $action         = "modules_install(\"{$module_name}\")";
                        $action_title   = $img_install;
                    }
                    else
                    {
                        $status_title   = 'модуль имеет неразрешенные зависимости и не может быть установлен';
                    }
                    break;
                }
                
                // Module is installed
                case '1':
                {
                    if ($module->getActive() == 1)
                    {
                        $status         = 'ok';
                        $status_title   = 'установлен и активен';
                        $action         = "modules_deactivate(\"{$module->name}\")";
                        $action_title   = $img_active;
                    }
                    else
                    {
                        $status         = '';
                        $status_title   = 'установлен, но не активен';
                        $action         = "modules_activate(\"{$module->name}\")";
                        $action_title   = $img_disable;
                    }
                    
                    // if it is not a system, add the link to remove
                    if ($group != 'system')
                    {
                        $action = array($action, "javascript: if (MsgOkCancel(\"Будут потеряны все данные модуля. \\nВы действительно хотите удалить модуль {$module_name}?\")) { modules_uninstall(\"{$module_name}\", \"{$group}\"); }");
                        $action_title = array($action_title, $img_uninstall);
                    }
                    
                    break;
                }
            }
            
            if (!empty($module->info->dependencies) > 0)
            {
                $module_title.= '<br/>Для работы требуются модули: ';
                
                foreach ($module->info->dependencies as $module_dependence_name => $dependence_active)
                {
                    if ($dependence_active == 1)
                        $module_title.= ' <font color="green">'. $module_dependence_name .'</font> ';
                    else
                        $module_title.= ' <font color="red">'. $module_dependence_name .'</font> ';
                }
            }
            
            if ($status == 'error_module_info') $row_class = 'row-error';
            
            $module_action = '';
            if (is_array($action))
            {
                for ($i=0; $i<count($action); $i++)
                    $module_action.= "<a class='js-link' onclick='". $action[$i] ."'>". $action_title[$i] ."</a> ";
            }
            elseif ($action_title != '' && $action != '')
                $module_action = "<a class='js-link' onclick='". $action ."'>". $action_title ."</a>";
            
            $row = array('cells' => array($module->name, $module->title, $module_action, $status_title));
            if (!empty($row_class))
                $row['class'] = $row_class;
            
            $rows[] = $row;
        }
        
        $form->addRows($rows);
        $output = $form->render();
        
        return $output;
    }
    
    // The list of options
    public function optionsAction()
    {
        $json = array();
        
        $json[] = array('title'=>'Системные модули', 'href'=>'#modules/list/system' );
        $json[] = array('title'=>'Дополнительные модули', 'href'=>'#modules/list/custom' );
        // $json[] = array('title'=>'Настройки', 'href'=>'#modules/settings' );
        
        $output = json_encode($json);
        
        return $output;
    }
    
    public function installAction($name)
    {
        if (empty($name))
            return 'Модуль не найден';
        
        $module = new Module($name);
        $module->createTables();
        $module->createDataTypes();
        $module->install();
    }
    
    public function uninstallAction($name)
    {
        if (empty($name))
            return 'Модуль не найден';
        
        $module = new Module($name);
        $module->uninstall();
    }
    
    public function activateAction($name)
    {
        if (empty($name))
            return 'Модуль не найден';
        
        $module = new Module($name);
        $module->setActive(1);
        $module->save();
    }
    
    public function disableAction($name)
    {
        if (empty($name))
            return 'Модуль не найден';
        
        $module = new Module($name);
        $module->setActive(0);
        $module->save();
    }
    
    
    private function getList($group)
    {
        if (empty($group) || !in_array($group, array('system', 'custom')))
            return false;
        
        $modules = array();
        
        // Open the directory with modules
        $path = $this->conf->root_dir .'/'. $group .'/modules';
        $descr = @opendir($path);
        if ($descr)
        {
            // Make the current directory
            chdir($path);
            
            while ($obj = readdir($descr))
                if (is_dir($obj))
                    if (file_exists(UrlEncode($obj) .'/info.php'))
                        $modules[$obj] = array('info' => $this->conf->root_dir .'/'. $group .'/modules/'. $obj .'/info.php');
        }
        
        return $modules;
    }
}